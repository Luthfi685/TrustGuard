<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\UserProgress;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ReportController extends Controller
{
    /**
     * Tampilan Halaman Laporan Komunitas (/report).
     */
    public function index(Request $request)
    {
        $initialUrl = $request->query('url', '');
        $reports = Report::latest()->paginate(8);
        $totalReports = Report::count();
        $verifiedReports = Report::where('status', 'verified')->count();

        return view('report', compact('reports', 'totalReports', 'verifiedReports', 'initialUrl'));
    }

    /**
     * Menyimpan Laporan Situs Mencurigakan (/report).
     */
    public function store(Request $request)
    {
        $request->validate([
            'url' => 'required|string|max:1000',
            'category' => 'required|string|in:Phishing,Penipuan,Website Palsu,Pencurian Data,Tautan Mencurigakan',
            'description' => 'required|string|min:15|max:2000',
        ], [
            'url.required' => 'URL situs yang dicurigai wajib diisi.',
            'category.required' => 'Pilihlah kategori indikasi ancaman.',
            'description.required' => 'Berikan penjelasan singkat mengenai indikasi bahaya yang Anda temukan.',
            'description.min' => 'Penjelasan minimal 15 karakter agar membantu tim verifikasi.',
        ]);

        $rawUrl = trim($request->input('url'));
        if (!preg_match('~^https?://~i', $rawUrl)) {
            $rawUrl = 'https://' . $rawUrl;
        }

        $parsed = parse_url($rawUrl);
        $domain = strtolower($parsed['host'] ?? 'domain-tidak-valid');
        if (str_starts_with($domain, 'www.')) {
            $domain = substr($domain, 4);
        }

        $report = Report::create([
            'url' => $rawUrl,
            'domain' => $domain,
            'category' => $request->input('category'),
            'description' => $request->input('description'),
            'status' => 'pending',
            'submitter_ip' => $request->ip(),
        ]);

        // Tambahkan Poin Apresiasi Komunitas ke Sesi Pengguna (+50 XP)
        $sessionId = Session::getId();
        $progress = UserProgress::getForSession($sessionId);
        $progress->increment('points', 50);
        $progress->refresh(); // Refresh agar nilai points terbaru terbaca

        // Kenaikan level otomatis setiap 200 poin
        $newLevel = min(5, (int)floor($progress->points / 200) + 1);
        if ($newLevel > $progress->level) {
            $progress->update(['level' => $newLevel]);
        }

        return redirect()->route('report.index')->with('success', 'Laporan berhasil dikirim! Anda mendapatkan +50 Poin Kontribusi Komunitas.');
    }
}
