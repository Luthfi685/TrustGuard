# PROPOSAL LOMBA WEB DEVELOPMENT
## SWITCHFEST 2026

---

### **JUDUL KARYA**
**TrustGuard: Platform Keamanan Siber Interaktif Berbasis Trust Score & Edukasi Tergamifikasi untuk Menciptakan Ekosistem Web yang Tepercaya**

**Tagline:** *"Browse with confidence. Know before you trust."*  
**Tema Lomba:** *NextGen Secure: Building the Future of Trusted Web Ecosystems*  
**Kategori UN SDG:** **SDG 9: Industry, Innovation, and Infrastructure** (Sub-pilar: Infrastruktur Digital & Keamanan Ekosistem Siber)

---

## BAB I: LATAR BELAKANG & IDENTIFIKASI MASALAH

### 1.1 Latar Belakang Masalah
Pertumbuhan infrastruktur siber dan ekonomi digital di Indonesia berkembang sangat pesat. Namun, peningkatan konektivitas ini berbanding lurus dengan tingginya ancaman kejahatan siber (*cybercrime*). Berdasarkan data Badan Siber dan Sandi Negara (BSSN), serangan rekayasa sosial (*social engineering*) seperti *phishing*, situs web penipuan (*scam sites*), dan malware meningkat signifikan setiap tahunnya.

Masyarakat umum seringkali menjadi korban karena keterbatasan pemahaman teknis. Data keamanan seperti Sertifikat SSL/TLS, umur domain (*RDAP/WHOIS*), HTTP Security Headers, serta status blacklist Google Safe Browsing umumnya tersaji dalam istilah teknis yang rumit (*raw data*). Akibatnya, pengguna internet tidak dapat membedakan mana tautan yang aman dan mana yang berbahaya sebelum melakukan transaksi atau memasukkan data sensitif.

### 1.2 Relevansi dengan UN SDG 9 (Industry, Innovation, and Infrastructure)
United Nations Sustainable Development Goal 9 (UN SDG 9) menekankan pentingnya pembangunan infrastruktur yang tangguh, peningkatan industri inklusif, serta pemupukan inovasi. Di era transformasi digital, **infrastruktur internet yang aman dan terpercaya (Trusted Web Ecosystem) merupakan fondasi utama dari infrastruktur digital yang berkelanjutan**. Tanpa adanya jaminan keamanan siber, kepercayaan masyarakat terhadap ekonomi digital akan runtuh.

TrustGuard hadir sebagai inovasi infrastruktur digital cerdas yang tidak hanya mengamankan pengguna dari potensi kejahatan siber, tetapi juga membangun budaya kesadaran siber melalui pendidikan tergamifikasi.

### 1.3 Rumusan Masalah
1. Bagaimana mengonversi data keamanan siber yang kompleks (SSL, RDAP, Safe Browsing, Security Headers) menjadi indikator visual yang intuitif (*Trust Score 0-100*) bagi pengguna awam?
2. Bagaimana mencegah ancaman serangan server (*Server-Side Request Forgery / SSRF*) pada aplikasi pemindai URL otomatis?
3. Bagaimana membangun ekosistem keamanan web yang partisipatif melalui edukasi interaktif dan modul pelaporan berbasis komunitas?

### 1.4 Tujuan & Manfaat
- **Tujuan Utama**: Membangun aplikasi web pemindai & edukasi keamanan siber berbasis Laravel yang cepat, presisi, responsif, dan aman.
- **Manfaat bagi Pengguna**: Memperoleh keputusan cepat sebelum mengklik tautan mencurigakan serta meningkatkan literasi keamanan siber (*digital safety literacy*).
- **Manfaat bagi Ekosistem Digital**: Menurunkan tingkat keberhasilan serangan *phishing* dan penipuan daring di Indonesia.

---

## BAB II: METODE, ARSITEKTUR, FITUR, DAN SOLUSI TEKNIS

### 2.1 Arsitektur Aplikasi & Tech Stack
TrustGuard dibangun menggunakan arsitektur modern berkinerja tinggi:
- **Backend Framework**: Laravel 11 / 12 (PHP 8.3+)
- **Database Engine**: MySQL 8.0 (Relational Database Management System)
- **Frontend Stack**: Laravel Blade Templating + Tailwind CSS 3.4 + Vanilla JavaScript (AJAX Fetch API)
- **Integrasi Protokol Keamanan**:
  1. *Native PHP SSL Stream Inspection* (`openssl_x509_parse`, socket stream context)
  2. *RDAP Protocol API* (`https://rdap.org/domain/{domain}`) untuk identifikasi umur dan registrar domain.
  3. *Google Safe Browsing API v4* untuk pencocokan database global ancaman malware/phishing.

```
                    +------------------------------------------+
                    |           Pengguna / Browser             |
                    +--------------------+---------------------+
                                         |
                                  HTTP / AJAX Request
                                         v
                    +------------------------------------------+
                    |          DomainCheckController           |
                    |     (Validasi Input & SSRF Defense)      |
                    +--------------------+---------------------+
                                         |
                                         v
                    +------------------------------------------+
                    |          DomainCheckerService            |
                    |                                          |
                    |  +------------------------------------+  |
                    |  | 1. SSL Certificate Stream Inspector|  |
                    |  | 2. RDAP Protocol Lookup (Age Check)|  |
                    |  | 3. Google Safe Browsing Threat API |  |
                    |  | 4. Security Headers Audit          |  |
                    |  +------------------------------------+  |
                    +--------------------+---------------------+
                                         |
                             Trust Score Engine (0-100)
                                         |
                                         v
                    +------------------------------------------+
                    |       Database MySQL (Scans Table)       |
                    +------------------------------------------+
```

### 2.2 Algoritma Trust Score Engine (0–100)
Skor kepercayaan disetir oleh kalkulasi formula pembobotan multi-faktor:

\[
\text{Trust Score} = w_1 S_{\text{SSL}} + w_2 S_{\text{RDAP}} + w_3 S_{\text{SafeBrowsing}} + w_4 S_{\text{Headers}} - P_{\text{TLD}}
\]

Dimana pembobotan yang diterapkan adalah:
- **SSL Certificate Weight ($w_1 = 30\%$)**: Menguji keberadaan SSL, masa berlaku sertifikat, validitas rantai sertifikat (*chain issuer*), dan algoritma enkripsi.
- **Domain Age Weight ($w_2 = 25\%$)**: Domain berusia < 30 hari mendapat nilai rendah (risiko phishing baru tinggi), domain berusia > 1 tahun mendapat poin penuh.
- **Threat Intelligence Weight ($w_3 = 25\%$)**: Deteksi ancaman terdaftar pada Google Safe Browsing / database malware.
- **Security Headers Weight ($w_4 = 15\%$)**: Audit HTTP response headers seperti `Strict-Transport-Security` (HSTS), `Content-Security-Policy` (CSP), dan `X-Frame-Options`.
- **Port / Risk Penalty ($P_{\text{TLD}} = 5\%$)**: Penalti untuk penggunaan domain TLD berisiko tinggi atau penggunaan IP publik langsung tanpa nama domain.

#### Kategori Status Keamanan:
- **80 – 100**: **Terpercaya (Safe)** — Berwarna Hijau (`#22C55E`)
- **60 – 79**: **Perlu Waspada (Warning)** — Berwarna Kuning (`#F59E0B`)
- **0 – 59**: **Berisiko Tinggi (Danger)** — Berwarna Merah (`#EF4444`)

### 2.3 Solusi Keamanan: SSRF (Server-Side Request Forgery) Defense
Untuk mencegah penyerang memanfaatkan pemindai URL TrustGuard untuk melakukan *port scanning* atau akses ke infrastruktur internal (localhost/intranet), controller menerapkan sanitasi ketat:
1. **Filter Skema Protocol**: Hanya mengizinkan `http://` dan `https://`.
2. **Pemeriksaan Loopback & Private IP Range**:
   - Memblokir `127.0.0.0/8`, `10.0.0.0/8`, `172.16.0.0/12`, `192.168.0.0/16`, `0.0.0.0`, dan `localhost`.
   - Menggunakan `dns_get_record()` / `gethostbyname()` untuk memastikan IP hasil resolusi domain bukan alamat internal.

### 2.4 Fitur-Fitur Utama Aplikasi
1. **Landing Page (`/`)**: Hero banner interaktif, live counter statistik pemindaian real-time, 3-step *How It Works*, dan penjelasan pilar keamanan.
2. **Interactive Live Scanner (`/scan`)**: Pemindai URL dengan indikator langkah berbasis AJAX real-time (*URL Validation -> Domain Lookup -> SSL Check -> Calculating Score*).
3. **Detail Analysis Report (`/result/{id}`)**: Visualisasi lingkaran Trust Score (SVG Circle Gauge), breakdown metrik keamanan teknis, penjelas bahasa awam (*Plain-English Explanations*), dan langkah tindakan keamanan.
4. **Executive Security Dashboard (`/dashboard`)**: Ringkasan statistik situs yang diperiksa, grafik distribusi tingkat keamanan, serta riwayat pemindaian terkini.
5. **Digital Safety Academy & Gamification (`/learn`)**: Sistem badge bertingkat (Level 1 *Digital Beginner* hingga Level 5 *Trust Guardian*), perolehan poin pengalaman, serta kuis simulasi membedakan tautan aman vs phishing.
6. **Community Report Module (`/report`)**: Formulir pelaporan situs berbahaya berbasis partisipasi masyarakat (kategori: Phishing, Penipuan, Website Palsu, Pencurian Data, Tautan Mencurigakan) dengan umpan riwayat laporan terkini.

---

## BAB III: DESAIN ANTARMUKA, SKEMA DATABASE, DAN PENGUJIAN

### 3.1 Desain Antarmuka (UI/UX Specifications)
Tampilan dirancang dengan estetika **Modern Dark Cybersecurity Aesthetic** untuk menciptakan kesan profesional, futuristik, dan tepercaya:
- **Navy Background**: `#0B1020`
- **Electric Blue Accent**: `#2563EB`
- **Safe Green**: `#22C55E`
- **Warning Yellow**: `#F59E0B`
- **Danger Red**: `#EF4444`
- **Neutral Light Text**: `#F8FAFC`
- **Card/Glass Panel**: `#131B2E` dengan perbatasan halus (`#1E293B`) dan aksen efek *backdrop-blur*.
- **Tipografi**: Font Google *Plus Jakarta Sans* untuk keterbacaan tinggi.

### 3.2 Skema Database (MySQL Schema)

#### Tabel `scans`
| Nama Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | BigInt (PK, Auto Increment) | Identifikasi unik scan |
| `url` | Text | URL lengkap yang diperiksa |
| `domain` | Varchar(255) | Domain target (indeks) |
| `trust_score` | Integer | Skor akhir (0-100) |
| `status` | Enum('safe', 'warning', 'danger') | Status kategori risiko |
| `ssl_info` | JSON | Detail metadata SSL (Issuer, Expiry, Days Left) |
| `rdap_info` | JSON | Metadata registrasi domain (Registrar, Created Date, Age Days) |
| `threat_info` | JSON | Respon ancaman Google Safe Browsing |
| `header_info` | JSON | Audit header HSTS, CSP, X-Frame-Options |
| `recommendations` | JSON | Daftar rekomendasi aksi bahasa awam |
| `ip_address` | Varchar(45) | IP publik dari domain target |
| `created_at` | Timestamp | Waktu pemindaian dilakukan |

#### Tabel `reports`
| Nama Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | BigInt (PK, Auto Increment) | ID Laporan |
| `url` | Text | URL yang dilaporkan |
| `domain` | Varchar(255) | Domain situs |
| `category` | Varchar(100) | Phishing, Penipuan, Website Palsu, dll. |
| `description` | Text | Uraian indikasi bahaya dari pengguna |
| `status` | Enum('pending', 'verified', 'rejected') | Status verifikasi laporan |
| `submitter_ip` | Varchar(45) | Alamat IP pelapor |
| `created_at` | Timestamp | Tanggal pelaporan |

#### Tabel `user_progress`
| Nama Kolom | Tipe Data | Keterangan |
|---|---|---|
| `id` | BigInt (PK, Auto Increment) | ID Progress |
| `session_id` | Varchar(255) | Token sesi unik pengguna |
| `points` | Integer | Total poin kuis & aktivitas |
| `level` | Integer | Peringkat level (1 - 5) |
| `completed_quizzes` | JSON | Array ID kuis yang telah diselesaikan |
| `updated_at` | Timestamp | Terakhir diperbarui |

### 3.3 Rencana Pengujian (Verification Plan)
1. **Pengujian Fungsionalitas & Unit Testing**:
   - Memastikan parser `DomainCheckerService` secara akurat menghitung sisa hari berlaku SSL.
   - Memastikan kalkulasi umur domain dari respon RDAP mengembalikan angka yang sesuai.
2. **Pengujian Keamanan (SSRF Defense Check)**:
   - Input `http://127.0.0.1`, `http://localhost`, `http://10.0.0.1` harus secara otomatis ditolak dengan pesan error yang jelas.
3. **Pengujian Responsivitas UI/UX**:
   - Memastikan antarmuka tampil sempurna di resolusi Smartphone (375px), Tablet (768px), dan Desktop (1440px+).

---

### KESIMPULAN
Aplikasi **TrustGuard** menggabungkan inovasi teknologi pemindaian domain native, integrasi intelijen ancaman global, serta edukasi tergamifikasi yang selaras dengan nilai-nilai **UN SDG 9**. Dengan rancangan arsitektur yang solid, perlindungan siber dari SSRF, dan visualisasi UI/UX yang memukau, TrustGuard siap menjadi solusi terdepan dalam kompetisi SWITCHFEST 2026.
