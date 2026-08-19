<?php

namespace Database\Seeders;

use App\Models\Scan;
use App\Models\Report;
use App\Models\UserProgress;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Sample Scans
        Scan::create([
            'url' => 'https://google.com',
            'domain' => 'google.com',
            'scheme' => 'https',
            'ip_address' => '142.250.190.46',
            'trust_score' => 98,
            'status' => 'safe',
            'ssl_info' => [
                'has_ssl' => true,
                'is_valid' => true,
                'issuer' => 'GTS CA 1C3',
                'subject' => 'google.com',
                'valid_to' => '2026-11-20 00:00:00',
                'days_remaining' => 93,
                'message' => 'Sertifikat SSL Aktif & Valid (Berlaku hingga 93 hari ke depan oleh GTS CA 1C3).',
            ],
            'rdap_info' => [
                'registered' => true,
                'created_at' => '1997-09-15',
                'age_days' => 10565,
                'age_years' => 28.9,
                'registrar' => 'MarkMonitor Inc.',
                'is_new_domain' => false,
                'message' => 'Domain terdaftar sejak 28.9 tahun lalu (10565 hari) melalui MarkMonitor Inc..',
            ],
            'threat_info' => [
                'is_malicious' => false,
                'threat_type' => 'NONE',
                'source' => 'Google Safe Browsing API',
                'message' => 'Tidak ditemukan catatan ancaman phishing atau malware pada domain ini.',
            ],
            'header_info' => [
                'headers' => [
                    'strict-transport-security' => ['label' => 'HSTS', 'present' => true, 'value' => 'max-age=31536000', 'desc' => 'HSTS Active'],
                    'content-security-policy' => ['label' => 'CSP', 'present' => true, 'value' => 'script-src ...', 'desc' => 'CSP Active'],
                    'x-frame-options' => ['label' => 'X-Frame-Options', 'present' => true, 'value' => 'SAMEORIGIN', 'desc' => 'Active'],
                    'x-content-type-options' => ['label' => 'X-Content-Type-Options', 'present' => true, 'value' => 'nosniff', 'desc' => 'Active'],
                ],
                'present_count' => 4,
                'total_count' => 4,
                'header_score_pct' => 100,
            ],
            'recommendations' => [
                ['type' => 'safe', 'text' => 'Situs ini memenuhi standar enkripsi SSL, memiliki reputasi domain baik, dan aman untuk dijelajahi.'],
            ],
        ]);

        Scan::create([
            'url' => 'http://bca-mobile-verifikasi.xyz',
            'domain' => 'bca-mobile-verifikasi.xyz',
            'scheme' => 'http',
            'ip_address' => '103.145.22.18',
            'trust_score' => 25,
            'status' => 'danger',
            'ssl_info' => [
                'has_ssl' => false,
                'is_valid' => false,
                'issuer' => 'Tidak Ada (Tautan HTTP Tidak Terenkripsi)',
                'subject' => 'bca-mobile-verifikasi.xyz',
                'days_remaining' => 0,
                'message' => 'Situs menggunakan protokol HTTP biasa yang tidak terenkripsi. Data sensitif dapat diintersepsi.',
            ],
            'rdap_info' => [
                'registered' => true,
                'created_at' => date('Y-m-d', strtotime('-5 days')),
                'age_days' => 5,
                'age_years' => 0.0,
                'registrar' => 'NameSilo LLC',
                'is_new_domain' => true,
                'message' => 'DOMAIN SANGAT BARU! Didaftarkan 5 hari lalu (NameSilo LLC). Phishing sering menggunakan domain baru.',
            ],
            'threat_info' => [
                'is_malicious' => false,
                'is_suspicious' => true,
                'threat_type' => 'HEURISTIC_RISK',
                'source' => 'TrustGuard Threat Heuristics Engine',
                'message' => "PERINGATAN: Menggunakan kata kunci rawan penipuan 'verify-account' & Domain menggunakan TLD reputasi rendah '.xyz'.",
            ],
            'header_info' => [
                'headers' => [
                    'strict-transport-security' => ['label' => 'HSTS', 'present' => false, 'value' => 'Tidak Diaktifkan', 'desc' => 'HSTS Missing'],
                    'content-security-policy' => ['label' => 'CSP', 'present' => false, 'value' => 'Tidak Diaktifkan', 'desc' => 'CSP Missing'],
                    'x-frame-options' => ['label' => 'X-Frame-Options', 'present' => false, 'value' => 'Tidak Diaktifkan', 'desc' => 'Missing'],
                    'x-content-type-options' => ['label' => 'X-Content-Type-Options', 'present' => false, 'value' => 'Tidak Diaktifkan', 'desc' => 'Missing'],
                ],
                'present_count' => 0,
                'total_count' => 4,
                'header_score_pct' => 0,
            ],
            'recommendations' => [
                ['type' => 'danger', 'text' => 'JANGAN BUKA SITUS INI! Domain kloning perbankan menggunakan TLD .xyz tanpa enkripsi SSL.'],
                ['type' => 'danger', 'text' => 'Hindari memasukkan kata sandi atau data pribadi! Situs ini tidak menggunakan enkripsi HTTPS.'],
            ],
        ]);

        // 2. Seed Sample Reports
        Report::create([
            'url' => 'http://bca-mobile-verifikasi.xyz',
            'domain' => 'bca-mobile-verifikasi.xyz',
            'category' => 'Phishing',
            'description' => 'Menerima pesan WhatsApp yang mengaku dari customer service BCA meminta pembaruan tarif transaksi dengan mengklik tautan ini.',
            'status' => 'verified',
            'submitter_ip' => '180.252.12.99',
        ]);

        Report::create([
            'url' => 'https://tokopedia-gebyar-promo2026.com',
            'domain' => 'tokopedia-gebyar-promo2026.com',
            'category' => 'Penipuan',
            'description' => 'Website mengklaim pemenang undian e-commerce Tokopedia dan meminta transfer biaya verifikasi pemenang sebesar 250 ribu.',
            'status' => 'pending',
            'submitter_ip' => '114.122.45.10',
        ]);
    }
}
