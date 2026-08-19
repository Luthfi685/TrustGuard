<?php

namespace App\Http\Controllers;

use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LearnController extends Controller
{
    /**
     * Data Kuis Edukasi Simulasi Phishing & Link Safe.
     */
    protected array $quizzes = [
        [
            'id' => 'q1',
            'title' => 'Simulasi Tautan Bank WhatsApp',
            'category' => 'Phishing Alert',
            'points' => 100,
            'scenario' => 'Anda menerima pesan WhatsApp dari nomor asing: "Yth Nasabah BCA, syarat & tarif transaksi baru berlaku. Silakan klik https://bca-mobile-verifikasi.xyz untuk konfirmasi gratis."',
            'url_display' => 'https://bca-mobile-verifikasi.xyz',
            'options' => [
                ['id' => 'a', 'text' => 'Buka link & isi formulir verifikasi'],
                ['id' => 'b', 'text' => 'Abaikan dan blokir nomor penerbit pesan (Phishing Domain .xyz)'],
                ['id' => 'c', 'text' => 'Sebarkan ke grup keluarga'],
            ],
            'correct_option' => 'b',
            'explanation' => 'TEPAT SEKALI! Bank resmi (seperti BCA) TIDAK PERNAH menggunakan nama domain gratis/murah berakhiran .xyz atau domain pihak ketiga seperti "bca-mobile-verifikasi.xyz". Domain resmi BCA adalah bca.co.id.',
        ],
        [
            'id' => 'q2',
            'title' => 'Membedakan SSL & Enkripsi Tautan',
            'category' => 'HTTPS Security',
            'points' => 100,
            'scenario' => 'Sebuah situs toko online menawarkan diskon 90% dengan URL: http://tokopedia-promo-gebyar.com. Setelah diperiksa, situs tidak memiliki simbol gembok HTTPS.',
            'url_display' => 'http://tokopedia-promo-gebyar.com',
            'options' => [
                ['id' => 'a', 'text' => 'Situs Berbahaya! Tidak ada enkripsi HTTPS & menggunakan domain kloningan'],
                ['id' => 'b', 'text' => 'Aman belanja karena domain berakhiran .com'],
                ['id' => 'c', 'text' => 'Tetap bertransaksi menggunakan kartu kredit'],
            ],
            'correct_option' => 'a',
            'explanation' => 'BENAR! Kombinasi tidak adanya gembok HTTPS (hanya HTTP) dan nama domain kloningan adalah ciri utama penipuan e-commerce pencurian data kartu kredit.',
        ],
        [
            'id' => 'q3',
            'title' => 'Verifikasi Tautan Undangan Nikah APK',
            'category' => 'Malware Protection',
            'points' => 100,
            'scenario' => 'Seseorang mengirimkan file dengan nama "Surat_Undangan_Pernikahan.apk" di WhatsApp dan meminta Anda membukanya.',
            'url_display' => 'Surat_Undangan_Pernikahan.apk (File Attachment)',
            'options' => [
                ['id' => 'a', 'text' => 'Unduh dan install langsung di smartphone Android'],
                ['id' => 'b', 'text' => 'Hapus file segera & jangan pernah diinstall (Ancaman Malware Sniffer SMS/OTP)'],
                ['id' => 'c', 'text' => 'Kirim file tersebut ke teman lain'],
            ],
            'correct_option' => 'b',
            'explanation' => 'LUAR BIASA! File berekstensi .APK yang dikirim via obrolan biasa umumnya berisi Malware Trojan Sniffer yang dapat mencuri SMS verifikasi OTP perbankan.',
        ],
        [
            'id' => 'q4',
            'title' => 'Email Fake Security Alert Instagram',
            'category' => 'Email Phishing',
            'points' => 100,
            'scenario' => 'Anda menerima email bertajuk "Security Alert: Login mencurigakan dari Rusia". Email tersebut meminta Anda mengklik tautan "https://instagram-login-security.online" untuk mengamankan akun.',
            'url_display' => 'https://instagram-login-security.online',
            'options' => [
                ['id' => 'a', 'text' => 'Buka link & langsung ubah kata sandi Instagram'],
                ['id' => 'b', 'text' => 'Periksa pengirim asli email & laporkan sebagai Spam Phishing (Domain resmi adalah instagram.com)'],
                ['id' => 'c', 'text' => 'Balas email tersebut meminta bantuan CS'],
            ],
            'correct_option' => 'b',
            'explanation' => 'TEPAT! Instagram & Meta hanya mengirim email keamanan resmi dari domain instagram.com atau facebookmail.com, bukan domain murah seperti .online.',
        ],
        [
            'id' => 'q5',
            'title' => 'Identifikasi Subdomain Penipuan Cryptowallet',
            'category' => 'Domain Spoofing',
            'points' => 100,
            'scenario' => 'Sebuah iklan Google menunjukkan hasil pencarian teratas: "https://metamask.io.secure-vault.tech". Apakah tautan ini aman?',
            'url_display' => 'https://metamask.io.secure-vault.tech',
            'options' => [
                ['id' => 'a', 'text' => 'Sangat Berbahaya! Domain utamanya adalah secure-vault.tech, bukan metamask.io (Trik Subdomain Spoofing)'],
                ['id' => 'b', 'text' => 'Aman karena kata metamask.io berada di awal tautan'],
                ['id' => 'c', 'text' => 'Aman karena memiliki HTTPS'],
            ],
            'correct_option' => 'a',
            'explanation' => 'EXCELLENT! Penipu menggunakan trik Subdomain Spoofing di mana "metamask.io" dijadikan nama anak (subdomain) dari domain utama "secure-vault.tech".',
        ],
        [
            'id' => 'q6',
            'title' => 'Penipuan QR Code Tempat Umum (Quishing)',
            'category' => 'QR Code Security',
            'points' => 100,
            'scenario' => 'Anda menemukan stiker QR Code pembayaran parkir di tempat umum yang ditimpa stiker lain mengarah ke: "http://bayar-parkir-kilat.site".',
            'url_display' => 'http://bayar-parkir-kilat.site',
            'options' => [
                ['id' => 'a', 'text' => 'Langsung bayar via e-wallet'],
                ['id' => 'b', 'text' => 'Waspada Quishing! Periksa domain target sebelum menginput PIN e-wallet'],
                ['id' => 'c', 'text' => 'Foto stiker tersebut untuk kenang-kenangan'],
            ],
            'correct_option' => 'b',
            'explanation' => 'TEPAT SEKALI! Modus Quishing (QR Phishing) dilakukan dengan menimpa stiker QR fisik resmi dengan QR palsu yang mengarah ke gateway pembayaran ilegal.',
        ],
        [
            'id' => 'q7',
            'title' => 'Penipuan Loker Paruh Waktu & Deposit',
            'category' => 'Job Scam Alert',
            'points' => 100,
            'scenario' => 'Anda ditawari pekerjaan paruh waktu "Like & Subscribe YouTube" gaji Rp500rb/hari, namun diminta top-up deposit di situs: "https://tugas-komisi-member.xyz".',
            'url_display' => 'https://tugas-komisi-member.xyz',
            'options' => [
                ['id' => 'a', 'text' => 'Top-up Rp100.000 untuk mencoba komisi'],
                ['id' => 'b', 'text' => 'Tolak & blokir! Pekerjaan resmi tidak pernah meminta deposit uang terlebih dahulu'],
                ['id' => 'c', 'text' => 'Pinjam uang teman untuk deposit terbesar'],
            ],
            'correct_option' => 'b',
            'explanation' => 'HEBAT! Ciri utama penipuan Loker Paruh Waktu (Task Scam) adalah meminta korban mentransfer/deposit uang terlebih dahulu untuk klaim komisi.',
        ],
        [
            'id' => 'q8',
            'title' => 'Pencurian Sandi via Google Form / Docs',
            'category' => 'Form Phishing',
            'points' => 100,
            'scenario' => 'Anda menerima tautan Google Form dari grup obrolan yang meminta Anda memasukkan email, kata sandi, dan nomor kartu debit untuk "Syarat Bantuan Sosial".',
            'url_display' => 'https://docs.google.com/forms/d/e/...',
            'options' => [
                ['id' => 'a', 'text' => 'Isi data lengkap karena formulir dihosting oleh Google'],
                ['id' => 'b', 'text' => 'Jangan diisi! Lembaga resmi atau bank tidak pernah meminta kata sandi & PIN via Google Form'],
                ['id' => 'c', 'text' => 'Isi hanya kata sandi saja'],
            ],
            'correct_option' => 'b',
            'explanation' => 'BENAR! Walaupun domain docs.google.com resmi, siapa saja dapat membuat Google Form gratis. Google melarang pengumpulan kata sandi & data keuangan via Form.',
        ],
        [
            'id' => 'q9',
            'title' => 'SMS Palsu Resi Paket Ekspedisi',
            'category' => 'SMS Phishing (Smishing)',
            'points' => 100,
            'scenario' => 'Pesan SMS: "Paket JNE Anda tertahan karena salah alamat. Klik http://jne-express-lacak.online/bayar untuk bayar kurir Rp2.000."',
            'url_display' => 'http://jne-express-lacak.online/bayar',
            'options' => [
                ['id' => 'a', 'text' => 'Klik link & bayar Rp2.000 via transfer'],
                ['id' => 'b', 'text' => 'Abaikan SMS! Cek resi asli langsung di aplikasi/website resmi jne.co.id'],
                ['id' => 'c', 'text' => 'Telepon nomor pengirim SMS'],
            ],
            'correct_option' => 'b',
            'explanation' => 'TEPAT! Ekspedisi resmi seperti JNE menggunakan domain jne.co.id dan tidak pernah meminta pembayaran kompensasi kecil via link SMS tidak dikenal.',
        ],
        [
            'id' => 'q10',
            'title' => 'Wi-Fi Publik Tanpa Sandi (Evil Twin)',
            'category' => 'Network Security',
            'points' => 100,
            'scenario' => 'Di bandara, terdapat Wi-Fi gratis bernama "Free_Airport_WiFi". Ketika terhubung, muncul halaman pop-up meminta Anda login menggunakan akun Google atau Bank.',
            'url_display' => 'http://login-wifi-portal.local',
            'options' => [
                ['id' => 'a', 'text' => 'Langsung masukkan email & kata sandi Google agar terhubung internet'],
                ['id' => 'b', 'text' => 'Putuskan koneksi! Jangan pernah menginput kata sandi akun penting di captive portal Wi-Fi publik'],
                ['id' => 'c', 'text' => 'Gunakan Wi-Fi tersebut untuk transfer m-banking'],
            ],
            'correct_option' => 'b',
            'explanation' => 'SANGAT TEPAT! Peretas dapat membuat hotspot palsu (Evil Twin Attack) untuk menyadap data sensitif yang Anda ketikkan saat terhubung ke Wi-Fi tersebut.',
        ],
    ];

    /**
     * Tampilan Akademi Keamanan Digital (/learn).
     */
    public function index()
    {
        $sessionId = Session::getId();
        $userProgress = UserProgress::getForSession($sessionId);

        $levels = [
            1 => ['title' => 'Digital Beginner', 'min_points' => 0, 'icon' => '🛡️', 'color' => 'from-blue-500 to-indigo-600'],
            2 => ['title' => 'Cyber Explorer', 'min_points' => 200, 'icon' => '🔍', 'color' => 'from-teal-500 to-emerald-600'],
            3 => ['title' => 'Security Sentinel', 'min_points' => 400, 'icon' => '⚡', 'color' => 'from-amber-500 to-orange-600'],
            4 => ['title' => 'Threat Hunter', 'min_points' => 600, 'icon' => '🎯', 'color' => 'from-purple-500 to-pink-600'],
            5 => ['title' => 'Trust Guardian', 'min_points' => 800, 'icon' => '👑', 'color' => 'from-cyan-400 to-blue-600'],
        ];

        $quizzes = $this->quizzes;

        return view('learn', compact('userProgress', 'levels', 'quizzes'));
    }

    /**
     * Endpoint Submit Kuis AJAX (/api/quiz-submit).
     */
    public function submitQuiz(Request $request)
    {
        $request->validate([
            'quiz_id' => 'required|string',
            'answer' => 'required|string',
        ]);

        $quizId = $request->input('quiz_id');
        $userAnswer = $request->input('answer');

        $quiz = collect($this->quizzes)->firstWhere('id', $quizId);
        if (!$quiz) {
            return response()->json(['success' => false, 'message' => 'Kuis tidak ditemukan.'], 404);
        }

        $isCorrect = ($userAnswer === $quiz['correct_option']);

        $sessionId = Session::getId();
        $progress = UserProgress::getForSession($sessionId);

        $completed = $progress->completed_quizzes ?? [];

        $earnedPoints = 0;
        $levelUp = false;

        if ($isCorrect && !in_array($quizId, $completed)) {
            $earnedPoints = $quiz['points'] ?? 100;
            $completed[] = $quizId;
            $newPoints = $progress->points + $earnedPoints;
            $newLevel = min(5, (int)floor($newPoints / 200) + 1);

            if ($newLevel > $progress->level) {
                $levelUp = true;
            }

            $progress->update([
                'points' => $newPoints,
                'level' => $newLevel,
                'completed_quizzes' => $completed,
            ]);
        }

        return response()->json([
            'success' => true,
            'is_correct' => $isCorrect,
            'explanation' => $quiz['explanation'],
            'earned_points' => $earnedPoints,
            'total_points' => $progress->fresh()->points,
            'level' => $progress->fresh()->level,
            'level_title' => $progress->fresh()->level_title,
            'level_up' => $levelUp,
        ]);
    }
}
