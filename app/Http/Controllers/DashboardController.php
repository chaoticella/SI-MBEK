<?php

namespace App\Http\Controllers;

use App\Models\Kandang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Kandang stats
        $totalKandang = Kandang::count();
        $totalKapasitas = Kandang::sum('kapasitas_maksimal');
        $kandangJantan = Kandang::where('kategori', 'jantan')->count();
        $kandangBetina = Kandang::where('kategori', 'betina')->count();
        $kandangIsolasi = Kandang::where('kategori', 'isolasi')->count();

        // User stats
        $totalUsers = User::count();
        $totalPengelola = User::whereIn('role', ['pengelola', 'pemilik', 'admin'])->count();
        $totalPegawai = User::where('role', 'pegawai')->count();

        // Recent kandang items
        $recentKandangs = Kandang::latest()->take(5)->get();

        return view('dashboard.index', compact(
            'user',
            'totalKandang',
            'totalKapasitas',
            'kandangJantan',
            'kandangBetina',
            'kandangIsolasi',
            'totalUsers',
            'totalPengelola',
            'totalPegawai',
            'recentKandangs'
        ));
    }
}
