<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanPenjualanExport;






class LaporanController extends Controller
{



    public function index(Request $request)
    {
        $query = Penjualan::query();
    
        // Filter berdasarkan nama pembeli atau kasir
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('pengguna', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                })->orWhereHas('kasir', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
            });
        }
    
        // Filter berdasarkan rentang tanggal
        if ($request->filled('daterange') && strpos($request->daterange, ' - ') !== false) {
            $dates = explode(' - ', $request->daterange);
            if (count($dates) == 2) {
                try {
                    $startDate = Carbon::createFromFormat('Y-m-d', trim($dates[0]))->startOfDay();
                    $endDate = Carbon::createFromFormat('Y-m-d', trim($dates[1]))->endOfDay();
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                } catch (\Exception $e) {
                    // Abaikan jika parsing tanggal gagal
                }
            }
        }
    

        $penjualan = $query->latest('created_at')->paginate(20);
    
        return view('laporan.index', compact('penjualan'));
    }
    
    
    
    
    public function exportExcel(Request $request)
    {
        Log::info('Export Excel requested');
    
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $search = $request->query('search');
    
        $query = Penjualan::with(['detailPenjualan', 'pengguna', 'kasir']); // Tambahkan relasi
    
        // Filter berdasarkan rentang tanggal
        if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
    
        // Filter berdasarkan nama kasir atau pengguna
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('pengguna', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                })->orWhereHas('kasir', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
            });
        }
    
        $penjualan = $query->get();
    
        // Cek apakah ada data sebelum diekspor
        if ($penjualan->isEmpty()) {
            Log::warning('Export Excel: Tidak ada data yang ditemukan.');
            return redirect()->back()->with('error', 'Tidak ada data yang tersedia untuk diekspor.');
        }
    
        return Excel::download(new LaporanPenjualanExport($penjualan), 'Laporan_Penjualan.xlsx');
    }
    
    




    public function cetakpdf($id)
    {
        try {
            $penjualan = Penjualan::with(['kasir', 'pengguna', 'detailpenjualan.barang'])->find($id);
    
            if (!$penjualan) {
                return redirect()->back()->with('error', 'Data penjualan tidak ditemukan!');
            }
    
            $pdf = Pdf::loadView('laporan.nota', compact('penjualan'))
                ->setPaper('a5', 'portrait');
    
            // Download otomatis
            return response($pdf->download('nota_penjualan.pdf'), 200, [
                'Content-Type' => 'application/pdf',
            ]);
    
        } catch (\Exception $e) {
            Log::error('Error saat mencetak PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mencetak PDF.');
        }
    }




    public function show($id)
    {
        $penjualan = Penjualan::with(['detailPenjualan.barang', 'pengguna', 'kasir', 'diskon'])->findOrFail($id);
        return view('laporan.show', compact('penjualan'));
    }
}
