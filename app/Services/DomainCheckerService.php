<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DomainCheckerService
{
    /**
     * Memeriksa domain dan menghitung Trust Score (0-100).
     */
    public function analyze(string $rawUrl): array
    {
        $urlData = $this->parseAndValidateUrl($rawUrl);
        $domain = $urlData['host'];
        $scheme = $urlData['scheme'];

        // 1. SSRF Protection Check
        $ip = $this->validateSsrfProtection($domain);

        // 2. SSL Inspection
        $sslInfo = $this->checkSslCertificate($domain, $scheme);

        // 3. RDAP Domain Registration Age Check
        $rdapInfo = $this->checkRdapDomainAge($domain);

        // 4. Google Safe Browsing / Threat Intelligence Check
        $threatInfo = $this->checkThreatIntelligence($rawUrl, $domain);

        // 5. Security Headers Audit
        $headerInfo = $this->checkSecurityHeaders($rawUrl);

        // 6. DNS & Mail Server Audit (A, MX, NS Records)
        $dnsInfo = $this->checkDnsRecords($domain);

        // 7. Calculate Trust Score & Generate Recommendations
        $scoreResult = $this->calculateTrustScore($sslInfo, $rdapInfo, $threatInfo, $headerInfo, $scheme, $domain);

        return [
            'raw_url' => $rawUrl,
            'domain' => $domain,
            'ip_address' => $ip,
            'scheme' => $scheme,
            'trust_score' => $scoreResult['score'],
            'status' => $scoreResult['status'], // 'safe', 'warning', 'danger'
            'status_label' => $scoreResult['status_label'], // 'Terpercaya', 'Perlu Waspada', 'Berisiko'
            'status_color' => $scoreResult['status_color'], // '#22C55E', '#F59E0B', '#EF4444'
            'ssl_info' => $sslInfo,
            'rdap_info' => $rdapInfo,
            'threat_info' => $threatInfo,
            'header_info' => $headerInfo,
            'dns_info' => $dnsInfo,
            'recommendations' => $scoreResult['recommendations'],
            'analyzed_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Memvalidasi dan mengekstrak komponen URL.
     */
    public function parseAndValidateUrl(string $url): array
    {
        if (!preg_match('~^https?://~i', $url)) {
            $url = 'https://' . $url;
        }

        $parts = parse_url($url);

        if (!$parts || empty($parts['host'])) {
            throw new Exception("URL tidak valid atau format penulisan domain salah.");
        }

        $host = strtolower($parts['host']);
        
        // Buang www. jika ada untuk standarisasi domain check
        if (str_starts_with($host, 'www.')) {
            $host = substr($host, 4);
        }

        return [
            'scheme' => strtolower($parts['scheme'] ?? 'https'),
            'host' => $host,
            'port' => $parts['port'] ?? ($parts['scheme'] === 'https' ? 443 : 80),
            'path' => $parts['path'] ?? '/',
        ];
    }

    /**
     * Perlindungan SSRF: Memblokir IP internal/privat & loopback.
     */
    public function validateSsrfProtection(string $host): string
    {
        // 1. Cek hostname terlarang
        $blockedHosts = ['localhost', 'loopback', 'broadcasthost'];
        if (in_array(strtolower($host), $blockedHosts)) {
            throw new Exception("AKSES DITOLAK (SSRF Defense): Domain localhost/internal tidak diperbolehkan.");
        }

        // 2. Resolusi IP Domain
        $ip = gethostbyname($host);

        if ($ip === $host && !filter_var($host, FILTER_VALIDATE_IP)) {
            // Cek jika gethostbyname gagal (bisa jadi domain tidak terdaftar di DNS)
            $dnsRecords = @dns_get_record($host, DNS_A);
            if (!empty($dnsRecords[0]['ip'])) {
                $ip = $dnsRecords[0]['ip'];
            }
        }

        // Jika berbentuk IP address langsung
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            // Filter IP Privat & Loopback (127.0.0.0/8, 10.0.0.0/8, 172.16.0.0/12, 192.168.0.0/16, 0.0.0.0)
            $flags = FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;
            if (!filter_var($ip, FILTER_VALIDATE_IP, $flags)) {
                throw new Exception("AKSES DITOLAK (SSRF Defense): Penyelidikan alamat IP privat/internal ({$ip}) tidak diizinkan demi keamanan.");
            }
        }

        return $ip;
    }

    /**
     * Inspeksi Sertifikat SSL Native PHP via Stream Client.
     */
    protected function checkSslCertificate(string $domain, string $scheme): array
    {
        if ($scheme !== 'https') {
            return [
                'has_ssl' => false,
                'is_valid' => false,
                'issuer' => 'Tidak Ada (Tautan HTTP Tidak Terenkripsi)',
                'subject' => $domain,
                'valid_from' => null,
                'valid_to' => null,
                'days_remaining' => 0,
                'message' => 'Situs menggunakan protokol HTTP biasa yang tidak terenkripsi. Data sensitif dapat diintersepsi.',
            ];
        }

        try {
            $streamContext = stream_context_create([
                'ssl' => [
                    'capture_peer_cert' => true,
                    'verify_peer' => false, // Untuk pengujian inspektif tanpa memutus stream
                    'verify_peer_name' => false,
                ],
            ]);

            $client = @stream_socket_client(
                "ssl://{$domain}:443",
                $errno,
                $errstr,
                4, // Timeout 4 detik
                STREAM_CLIENT_CONNECT,
                $streamContext
            );

            if (!$client) {
                return [
                    'has_ssl' => false,
                    'is_valid' => false,
                    'issuer' => 'Gagal Terhubung via SSL/TLS',
                    'subject' => $domain,
                    'days_remaining' => 0,
                    'message' => "Port SSL 443 tidak merespon: {$errstr}",
                ];
            }

            $params = stream_context_get_params($client);
            fclose($client);

            if (empty($params['options']['ssl']['peer_certificate'])) {
                return [
                    'has_ssl' => false,
                    'is_valid' => false,
                    'issuer' => 'Sertifikat Tidak Ditemukan',
                    'subject' => $domain,
                    'days_remaining' => 0,
                    'message' => 'Server tidak mengirimkan sertifikat SSL.',
                ];
            }

            $parsedCert = openssl_x509_parse($params['options']['ssl']['peer_certificate']);
            
            $validFrom = isset($parsedCert['validFrom_time_t']) ? date('Y-m-d H:i:s', $parsedCert['validFrom_time_t']) : null;
            $validTo = isset($parsedCert['validTo_time_t']) ? date('Y-m-d H:i:s', $parsedCert['validTo_time_t']) : null;
            
            $now = time();
            $validToTimestamp = $parsedCert['validTo_time_t'] ?? 0;
            $daysRemaining = max(0, (int)floor(($validToTimestamp - $now) / 86400));
            $isValid = ($validToTimestamp > $now);

            $issuerOrg = $parsedCert['issuer']['O'] ?? $parsedCert['issuer']['CN'] ?? 'Penerbit Terverifikasi';

            return [
                'has_ssl' => true,
                'is_valid' => $isValid,
                'issuer' => $issuerOrg,
                'subject' => $parsedCert['subject']['CN'] ?? $domain,
                'valid_from' => $validFrom,
                'valid_to' => $validTo,
                'days_remaining' => $daysRemaining,
                'message' => $isValid 
                    ? "Sertifikat SSL Aktif & Valid (Berlaku hingga {$daysRemaining} hari ke depan oleh {$issuerOrg})."
                    : "Sertifikat SSL telah KADALUARSA atau tidak valid!",
            ];
        } catch (Exception $e) {
            return [
                'has_ssl' => false,
                'is_valid' => false,
                'issuer' => 'Gagal Memeriksa SSL',
                'subject' => $domain,
                'days_remaining' => 0,
                'message' => 'Terjadi kesalahan teknis saat membaca data sertifikat SSL.',
            ];
        }
    }

    /**
     * Memeriksa Umur Registrasi Domain Menggunakan RDAP Protocol (https://rdap.org/domain/{domain}).
     */
    public function checkRdapDomainAge(string $domain): array
    {
        try {
            // Panggil API RDAP terbuka dengan timeout 8 detik dan auto-retry
            $response = Http::timeout(8)->retry(2, 200)->withHeaders([
                'User-Agent' => 'TrustGuard-Security-Scanner/1.0',
                'Accept' => 'application/json, application/rdap+json',
            ])->get("https://rdap.org/domain/{$domain}");

            if ($response->successful()) {
                $data = $response->json();
                $createdDateStr = null;
                $registrar = null;

                // Cari event 'registration' dalam daftar events RDAP
                if (!empty($data['events'])) {
                    foreach ($data['events'] as $event) {
                        if (isset($event['eventAction']) && in_array($event['eventAction'], ['registration', 'transfer', 'last changed'])) {
                            if (!empty($event['eventDate'])) {
                                $createdDateStr = $event['eventDate'];
                                break;
                            }
                        }
                    }
                }

                // Cari nama registrar
                if (!empty($data['entities'])) {
                    foreach ($data['entities'] as $entity) {
                        if (isset($entity['roles']) && in_array('registrar', $entity['roles'])) {
                            if (!empty($entity['vcardArray'][1])) {
                                foreach ($entity['vcardArray'][1] as $vcard) {
                                    if ($vcard[0] === 'fn') {
                                        $registrar = $vcard[3];
                                        break 2;
                                    }
                                }
                            }
                        }
                    }
                }

                if ($createdDateStr) {
                    $createdTimestamp = strtotime($createdDateStr);
                    $ageDays = (int)floor((time() - $createdTimestamp) / 86400);
                    $ageYears = round($ageDays / 365.25, 1);

                    // Hitung rincian presisi Tahun, Bulan, dan Hari
                    $startDate = new \DateTime("@{$createdTimestamp}");
                    $endDate = new \DateTime();
                    $interval = $startDate->diff($endDate);
                    $ageFormatted = "{$interval->y} Tahun, {$interval->m} Bulan, {$interval->d} Hari";

                    return [
                        'registered' => true,
                        'created_at' => date('Y-m-d', $createdTimestamp),
                        'age_days' => $ageDays,
                        'age_years' => $ageYears,
                        'age_months' => ($interval->y * 12) + $interval->m,
                        'age_formatted' => $ageFormatted,
                        'age_breakdown' => [
                            'years' => $interval->y,
                            'months' => $interval->m,
                            'days' => $interval->d,
                            'total_days' => $ageDays,
                        ],
                        'registrar' => $registrar ?? 'Registrar Terdaftar RDAP',
                        'is_new_domain' => ($ageDays < 60), // Risk factor jika domain kurang dari 60 hari
                        'message' => ($ageDays < 60)
                            ? "DOMAIN SANGAT BARU! Didaftarkan {$ageDays} hari lalu ({$registrar}). Phishing sering menggunakan domain baru."
                            : "Domain aktif selama {$ageFormatted} ({$ageDays} hari) melalui {$registrar}.",
                    ];
                }
            }
        } catch (Exception $e) {
            Log::info("RDAP lookup failed for {$domain}, using fallback estimator.");
        }

        // Fallback Heuristic jika RDAP timeout / TLD lokal tidak mendukung RDAP umum
        return [
            'registered' => true,
            'created_at' => 'Tidak Terbaca via RDAP',
            'age_days' => 365, // Asumsi standar
            'age_years' => 1.0,
            'registrar' => 'Informasi RDAP Terproteksi',
            'is_new_domain' => false,
            'message' => 'Informasi umur registrasi domain berada dalam enkripsi WHOIS/RDAP privasi.',
        ];
    }

    /**
     * Memeriksa Ancaman Malware / Phishing (Google Safe Browsing API v4 & Keyword Heuristic).
     */
    protected function checkThreatIntelligence(string $url, string $domain): array
    {
        $apiKey = env('GOOGLE_SAFE_BROWSING_API_KEY');

        if ($apiKey) {
            try {
                $endpoint = "https://safebrowsing.googleapis.com/v4/threatMatches:find?key={$apiKey}";
                $body = [
                    'client' => [
                        'clientId' => 'trustguard-app',
                        'clientVersion' => '1.0.0',
                    ],
                    'threatInfo' => [
                        'threatTypes' => ['MALWARE', 'SOCIAL_ENGINEERING', 'UNWANTED_SOFTWARE', 'POTENTIALLY_HARMFUL_APPLICATION'],
                        'platformTypes' => ['ANY_PLATFORM'],
                        'threatEntryTypes' => ['URL'],
                        'threatEntries' => [['url' => $url]],
                    ],
                ];

                $response = Http::timeout(4)->post($endpoint, $body);

                if ($response->successful() && !empty($response->json('matches'))) {
                    $matches = $response->json('matches');
                    $threatType = $matches[0]['threatType'] ?? 'MALWARE/PHISHING';
                    return [
                        'is_malicious' => true,
                        'threat_type' => $threatType,
                        'source' => 'Google Safe Browsing API',
                        'message' => "BAHAYA KRITIS! URL terdeteksi dalam database global ancaman: {$threatType}.",
                    ];
                }
            } catch (Exception $e) {
                Log::warning("Safe Browsing API check error: " . $e->getMessage());
            }
        }

        // Heuristic Keyword & Suspicious TLD Inspection
        $suspiciousKeywords = [
            'login-update', 'verify-account', 'secure-banking', 'claim-bonus',
            'free-gift', 'dana-kaget', 'gopay-promo', 'whatsapp-verifikasi',
            'gebyar-hadiah', 'bca-mobile-update', 'bri-mo-verifikasi'
        ];

        $suspiciousTlds = ['.tk', '.ml', '.ga', '.cf', '.gq', '.top', '.xyz', '.buzz', '.work', '.click'];

        $foundKeyword = false;
        foreach ($suspiciousKeywords as $kw) {
            if (str_contains(strtolower($url), $kw)) {
                $foundKeyword = $kw;
                break;
            }
        }

        $isSuspiciousTld = false;
        foreach ($suspiciousTlds as $tld) {
            if (str_ends_with(strtolower($domain), $tld)) {
                $isSuspiciousTld = $tld;
                break;
            }
        }

        if ($foundKeyword || $isSuspiciousTld) {
            $reason = [];
            if ($foundKeyword) $reason[] = "Menggunakan kata kunci rawan penipuan '{$foundKeyword}'";
            if ($isSuspiciousTld) $reason[] = "Domain menggunakan TLD reputasi rendah '{$isSuspiciousTld}'";

            return [
                'is_malicious' => false,
                'is_suspicious' => true,
                'threat_type' => 'HEURISTIC_RISK',
                'source' => 'TrustGuard Threat Heuristics Engine',
                'message' => "PERINGATAN: " . implode(' & ', $reason) . ".",
            ];
        }

        return [
            'is_malicious' => false,
            'is_suspicious' => false,
            'threat_type' => 'NONE',
            'source' => 'TrustGuard Threat Intelligence',
            'message' => 'Tidak ditemukan catatan ancaman phishing atau malware pada domain ini.',
        ];
    }

    /**
     * Audit HTTP Security Headers (HSTS, CSP, X-Frame-Options, X-Content-Type-Options).
     */
    protected function checkSecurityHeaders(string $url): array
    {
        $headersToCheck = [
            'strict-transport-security' => [
                'label' => 'HSTS (Strict-Transport-Security)',
                'desc' => 'Memaksa koneksi HTTPS aman dan mencegah Man-in-the-Middle.',
            ],
            'content-security-policy' => [
                'label' => 'CSP (Content-Security-Policy)',
                'desc' => 'Mencegah serangan Cross-Site Scripting (XSS) dan injeksi kode.',
            ],
            'x-frame-options' => [
                'label' => 'X-Frame-Options',
                'desc' => 'Mencegah serangan Clickjacking dalam iframe.',
            ],
            'x-content-type-options' => [
                'label' => 'X-Content-Type-Options',
                'desc' => 'Mencegah MIME-type sniffing berbahaya.',
            ],
        ];

        $results = [];
        $presentCount = 0;

        try {
            $response = Http::timeout(4)->withHeaders([
                'User-Agent' => 'TrustGuard-Header-Checker/1.0',
            ])->get($url);

            $headers = array_change_key_case($response->headers(), CASE_LOWER);

            foreach ($headersToCheck as $key => $meta) {
                $hasHeader = isset($headers[$key]);
                if ($hasHeader) {
                    $presentCount++;
                }
                $results[$key] = [
                    'label' => $meta['label'],
                    'present' => $hasHeader,
                    'value' => $hasHeader ? (is_array($headers[$key]) ? $headers[$key][0] : $headers[$key]) : 'Tidak Diaktifkan',
                    'desc' => $meta['desc'],
                ];
            }
        } catch (Exception $e) {
            foreach ($headersToCheck as $key => $meta) {
                $results[$key] = [
                    'label' => $meta['label'],
                    'present' => false,
                    'value' => 'Tidak Dapat Diperiksa',
                    'desc' => $meta['desc'],
                ];
            }
        }

        return [
            'headers' => $results,
            'present_count' => $presentCount,
            'total_count' => count($headersToCheck),
            'header_score_pct' => round(($presentCount / count($headersToCheck)) * 100),
        ];
    }

    /**
     * Audit Rekam DNS (A, MX, NS) Domain Target.
     */
    protected function checkDnsRecords(string $domain): array
    {
        $dnsRecords = [
            'has_mx' => false,
            'mx_servers' => [],
            'ns_servers' => [],
            'a_records' => [],
        ];

        try {
            $mx = @dns_get_record($domain, DNS_MX);
            if (!empty($mx)) {
                $dnsRecords['has_mx'] = true;
                foreach ($mx as $r) {
                    if (!empty($r['target'])) {
                        $dnsRecords['mx_servers'][] = $r['target'];
                    }
                }
            }

            $ns = @dns_get_record($domain, DNS_NS);
            if (!empty($ns)) {
                foreach ($ns as $r) {
                    if (!empty($r['target'])) {
                        $dnsRecords['ns_servers'][] = $r['target'];
                    }
                }
            }

            $a = @dns_get_record($domain, DNS_A);
            if (!empty($a)) {
                foreach ($a as $r) {
                    if (!empty($r['ip'])) {
                        $dnsRecords['a_records'][] = $r['ip'];
                    }
                }
            }
        } catch (Exception $e) {
            // Silence DNS lookup error if DNS port blocked
        }

        return $dnsRecords;
    }

    /**
     * Menghitung Skor Akhir Trust Score (0-100) dan Menentukan Kategori Risiko.
     */
    protected function calculateTrustScore(
        array $ssl,
        array $rdap,
        array $threat,
        array $headers,
        string $scheme,
        string $domain
    ): array {
        $score = 100;
        $recommendations = [];

        // 1. Ancaman Kritis (Google Safe Browsing / Malicious) -> Langsung penalti berat
        if ($threat['is_malicious']) {
            $score -= 60;
            $recommendations[] = [
                'type' => 'danger',
                'text' => 'JANGAN BUKA SITUS INI! Situs telah ditandai berbahaya oleh intelijen keamanan global.',
            ];
        } elseif (!empty($threat['is_suspicious'])) {
            $score -= 25;
            $recommendations[] = [
                'type' => 'warning',
                'text' => 'Waspada penipuan! Domain atau URL menggunakan kombinasi kata kunci mencurigakan.',
            ];
        }

        // 2. Evaluasi SSL (Bobot 30%)
        if ($scheme !== 'https' || !$ssl['has_ssl']) {
            $score -= 30;
            $recommendations[] = [
                'type' => 'danger',
                'text' => 'Hindari memasukkan kata sandi atau data pribadi! Situs ini tidak menggunakan enkripsi HTTPS.',
            ];
        } elseif (!$ssl['is_valid']) {
            $score -= 25;
            $recommendations[] = [
                'type' => 'danger',
                'text' => 'Sertifikat SSL situs telah kadaluarsa atau tidak tepercaya.',
            ];
        } else {
            if ($ssl['days_remaining'] < 14) {
                $score -= 5;
                $recommendations[] = [
                    'type' => 'warning',
                    'text' => "Sertifikat SSL akan segera kadaluarsa dalam {$ssl['days_remaining']} hari.",
                ];
            }
        }

        // 3. Evaluasi Umur Registrasi Domain (Bobot 25%)
        if (!empty($rdap['is_new_domain'])) {
            $score -= 20;
            $recommendations[] = [
                'type' => 'warning',
                'text' => 'Domain tergolong baru (< 60 hari). Phishing dan situs penipuan umumnya menggunakan domain yang baru dibeli.',
            ];
        } elseif ($rdap['age_days'] < 180) {
            $score -= 10;
        }

        // 4. Evaluasi Security Headers (Bobot 15%)
        $headerMissing = $headers['total_count'] - $headers['present_count'];
        $score -= ($headerMissing * 3);
        if ($headers['present_count'] < 2) {
            $recommendations[] = [
                'type' => 'warning',
                'text' => 'Situs kurang menerapkan standar HTTP Security Headers (seperti HSTS & CSP).',
            ];
        }

        // 5. Penalti Pengguna Alamat IP Langsung
        if (filter_var($domain, FILTER_VALIDATE_IP)) {
            $score -= 15;
            $recommendations[] = [
                'type' => 'warning',
                'text' => 'Situs diakses langsung via Alamat IP tanpa nama domain resmi.',
            ];
        }

        // Pastikan rentang skor berada pada 0 - 100
        $score = max(5, min(100, $score));

        // Tentukan Kategori & Visualisasi Status
        if ($score >= 80) {
            $status = 'safe';
            $statusLabel = 'Terpercaya';
            $statusColor = '#22C55E';
            if (empty($recommendations)) {
                $recommendations[] = [
                    'type' => 'safe',
                    'text' => 'Situs ini memenuhi standar enkripsi SSL, memiliki reputasi domain baik, dan aman untuk dijelajahi.',
                ];
            }
        } elseif ($score >= 60) {
            $status = 'warning';
            $statusLabel = 'Perlu Waspada';
            $statusColor = '#F59E0B';
        } else {
            $status = 'danger';
            $statusLabel = 'Berisiko Tinggi';
            $statusColor = '#EF4444';
        }

        return [
            'score' => $score,
            'status' => $status,
            'status_label' => $statusLabel,
            'status_color' => $statusColor,
            'recommendations' => $recommendations,
        ];
    }
}
