<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\User;
use App\Models\Kategori;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBarang = Barang::count();
        $totalPengguna = User::where('role', 'pengguna')->count();
        $totalKategori = Kategori::count();
    
        // Mengambil barang yang stoknya di bawah atau sama dengan minimal_stok
        $barangHampirHabis = Barang::select('barang.*', DB::raw('COALESCE(stok_data.total_stock, 0) as total_stock'))
        ->leftJoin(DB::raw('(SELECT barang_id, SUM(jumlah_stok) as total_stock FROM barang_stok GROUP BY barang_id) as stok_data'), 
            'barang.id', '=', 'stok_data.barang_id')
        ->whereRaw('COALESCE(stok_data.total_stock, 0) <= barang.minimal_stok')
        ->get();
    
    


       

    
        // Ambil total uang_masuk per hari dalam 7 hari terakhir
        $weeklySales = DB::table('penjualan')
            ->selectRaw('DATE(created_at) as date, SUM(uang_masuk) as total')
            ->whereBetween('created_at', [
                now()->subDays(6)->startOfDay(),
                now()->endOfDay()
            ])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('total', 'date')
            ->toArray();
    
        $labels = [];
        $data = [];
    
        // Loop untuk mengambil data 7 hari terakhir
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $labels[] = Carbon::parse($date)->format('d M');
            
            // Cek apakah ada nilai di tanggal tersebut
            $data[] = isset($weeklySales[$date]) ? (int) $weeklySales[$date] * 10 : 0;
        }
    
        return view('index', compact('totalBarang', 'totalKategori', 'totalPengguna', 'barangHampirHabis', 'labels'))
            ->with('data', array_map('intval', $data));
    }
    
}
