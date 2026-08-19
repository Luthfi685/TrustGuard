<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Scan;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Kredensial admin sederhana (tanpa database user)
    private const ADMIN_USER = 'admin';
    private const ADMIN_PASS = 'trustguard2026';

    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */
    public function loginForm()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (
            $request->input('username') === self::ADMIN_USER &&
            $request->input('password') === self::ADMIN_PASS
        ) {
            session(['admin_logged_in' => true, 'admin_user' => self::ADMIN_USER]);
            return redirect()->route('admin.dashboard');
        }

        return back()->with('error', 'Username atau password salah!');
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['admin_logged_in', 'admin_user']);
        return redirect()->route('admin.login')->with('success', 'Berhasil logout.');
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard Admin
    |--------------------------------------------------------------------------
    */
    public function dashboard()
    {
        $totalScans   = Scan::count();
        $safeScans    = Scan::where('status', 'safe')->count();
        $warningScans = Scan::where('status', 'warning')->count();
        $dangerScans  = Scan::where('status', 'danger')->count();

        $totalReports   = Report::count();
        $pendingReports = Report::where('status', 'pending')->count();
        $verifiedReports = Report::where('status', 'verified')->count();

        $latestScans   = Scan::latest()->take(5)->get();
        $latestReports = Report::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalScans', 'safeScans', 'warningScans', 'dangerScans',
            'totalReports', 'pendingReports', 'verifiedReports',
            'latestScans', 'latestReports'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | Scan Management
    |--------------------------------------------------------------------------
    */
    public function scans(Request $request)
    {
        $query = Scan::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('domain', 'like', '%' . $request->search . '%');
        }

        $scans = $query->paginate(15)->withQueryString();
        return view('admin.scans', compact('scans'));
    }

    public function deleteScan($id)
    {
        Scan::findOrFail($id)->delete();
        return back()->with('success', 'Data scan berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | Report Management
    |--------------------------------------------------------------------------
    */
    public function reports(Request $request)
    {
        $query = Report::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->paginate(15)->withQueryString();
        return view('admin.reports', compact('reports'));
    }

    public function verifyReport($id)
    {
        Report::findOrFail($id)->update(['status' => 'verified']);
        return back()->with('success', 'Laporan berhasil diverifikasi.');
    }

    public function rejectReport($id)
    {
        Report::findOrFail($id)->update(['status' => 'rejected']);
        return back()->with('success', 'Laporan berhasil ditolak/ditandai tidak valid.');
    }

    public function deleteReport($id)
    {
        Report::findOrFail($id)->delete();
        return back()->with('success', 'Laporan berhasil dihapus.');
    }
}
