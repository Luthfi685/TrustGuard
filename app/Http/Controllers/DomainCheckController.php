<?php

namespace App\Http\Controllers;

use App\Models\Scan;
use App\Models\Report;
use App\Models\UserProgress;
use App\Services\DomainCheckerService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DomainCheckController extends Controller
{
    protected DomainCheckerService $checkerService;

    public function __construct(DomainCheckerService $checkerService)
    {
        $this->checkerService = $checkerService;
    }

    /**
     * Tampilan Landing Page (/).
     */
    public function index()
    {
        $realScansCount = Scan::count();
        $realSafeCount = Scan::where('status', 'safe')->count();
        $realRiskCount = Scan::whereIn('status', ['warning', 'danger'])->count();

        $totalChecked = $realScansCount;
        $trustedCount = $realSafeCount;
        $riskCount = $realRiskCount;

        $recentScans = Scan::latest()->take(6)->get();

        return view('index', compact('totalChecked', 'trustedCount', 'riskCount', 'recentScans'));
    }

    /**
     * Tampilan Live Scanner (/scan).
     */
    public function scanner(Request $request)
    {
        $initialUrl = $request->query('url', '');
        return view('scanner', compact('initialUrl'));
    }

    /**
     * Endpoint AJAX Pemindaian URL (/api/scan-ajax).
     */
    public function scanAjax(Request $request)
    {
        $request->validate([
            'url' => 'required|string|max:1000',
        ], [
            'url.required' => 'Silakan masukkan tautan atau nama domain yang ingin diperiksa.',
        ]);

        $url = trim($request->input('url'));

        try {
            // Jalankan analisis menyeluruh melalui Service (SSL, RDAP, Safe Browsing, SSRF Defense, Headers)
            $result = $this->checkerService->analyze($url);

            // Simpan hasil ke database MySQL
            $scan = Scan::create([
                'url' => $result['raw_url'],
                'domain' => $result['domain'],
                'scheme' => $result['scheme'],
                'ip_address' => $result['ip_address'],
                'trust_score' => $result['trust_score'],
                'status' => $result['status'],
                'ssl_info' => $result['ssl_info'],
                'rdap_info' => $result['rdap_info'],
                'threat_info' => $result['threat_info'],
                'header_info' => $result['header_info'],
                'recommendations' => $result['recommendations'],
            ]);

            // Tambahkan Bonus Poin Keahlian Digital (+20 XP per Scan)
            $sessionId = Session::getId();
            $progress = UserProgress::getForSession($sessionId);
            $progress->increment('points', 20);
            $progress->refresh(); // Refresh agar nilai points terbaru terbaca
            $newLevel = min(5, (int)floor($progress->points / 200) + 1);
            if ($newLevel > $progress->level) {
                $progress->update(['level' => $newLevel]);
            }

            return response()->json([
                'success' => true,
                'scan_id' => $scan->id,
                'redirect_url' => route('result', $scan->id),
                'result' => $result,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Tampilan Hasil Detail Analisis (/result/{id}).
     */
    public function result($id)
    {
        $scan = Scan::findOrFail($id);
        
        // Auto-enrich data RDAP jika pada scan sebelumnya sempat gagal/fallback
        if (empty($scan->rdap_info['created_at']) || $scan->rdap_info['created_at'] === 'Tidak Terbaca via RDAP' || empty($scan->rdap_info['age_formatted'])) {
            try {
                $freshRdap = $this->checkerService->checkRdapDomainAge($scan->domain);
                if (!empty($freshRdap['created_at']) && $freshRdap['created_at'] !== 'Tidak Terbaca via RDAP') {
                    $scan->update(['rdap_info' => $freshRdap]);
                    $scan->refresh();
                }
            } catch (\Throwable $e) {}
        }

        // Peringatan laporan komunitas jika ada domain yang sama dilaporkan masyarakat
        $communityReportsCount = Report::where('domain', $scan->domain)->count();

        return view('result', compact('scan', 'communityReportsCount'));
    }

    /**
     * Tampilan Executive Dashboard (/dashboard).
     */
    public function dashboard()
    {
        $totalScans = Scan::count();
        $safeScans = Scan::where('status', 'safe')->count();
        $warningScans = Scan::where('status', 'warning')->count();
        $dangerScans = Scan::where('status', 'danger')->count();

        $totalChecked = $totalScans;
        $safeCount = $safeScans;
        $warningCount = $warningScans;
        $dangerCount = $dangerScans;

        $recentScans = Scan::latest()->paginate(10);
        
        $sessionId = Session::getId();
        $userProgress = UserProgress::getForSession($sessionId);

        // Kalkulasi persentase Digital Safety Level pengguna
        $safetyLevelPct = min(100, max(20, ($userProgress->level * 20)));

        // Data grafik bulanan: jumlah scan per bulan di tahun berjalan dari DB
        $currentYear = now()->year;
        $monthlyRaw = Scan::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlyLabels = [];
        $monthlyData   = [];
        $currentMonth  = now()->month;
        $runningTotal  = 0;
        for ($m = 1; $m <= $currentMonth; $m++) {
            $runningTotal += $monthlyRaw[$m] ?? 0;
            $monthlyLabels[] = $monthNames[$m - 1];
            $monthlyData[]   = $runningTotal;
        }
        // Jika tidak ada data sama sekali, tampilkan 0 untuk bulan ini
        if (empty($monthlyLabels)) {
            $monthlyLabels = [$monthNames[$currentMonth - 1]];
            $monthlyData   = [0];
        }

        return view('dashboard', compact(
            'totalScans',
            'safeScans',
            'warningScans',
            'dangerScans',
            'totalChecked',
            'safeCount',
            'warningCount',
            'dangerCount',
            'recentScans',
            'userProgress',
            'safetyLevelPct',
            'monthlyLabels',
            'monthlyData'
        ));
    }

    /**
     * Endpoint AJAX Statistik Live Real-Time (/api/stats).
     */
    public function statsAjax()
    {
        $realScansCount = Scan::count();
        $realSafeCount = Scan::where('status', 'safe')->count();
        $realRiskCount = Scan::whereIn('status', ['warning', 'danger'])->count();

        $totalChecked = $realScansCount;
        $trustedCount = $realSafeCount;
        $riskCount = $realRiskCount;

        return response()->json([
            'success' => true,
            'totalChecked' => $totalChecked,
            'trustedCount' => $trustedCount,
            'riskCount' => $riskCount,
        ]);
    }
}
