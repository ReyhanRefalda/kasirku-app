<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Barang;
use App\Models\Diskon;
use App\Models\Penjualan;
use App\Models\BarangStok;
use Illuminate\Http\Request;
use App\Models\DetailPenjualan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;




class PenjualanController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'pengguna')->get();
        $barang = Barang::all();
        $diskon = Diskon::all();
        return view('kasir.index', compact('users', 'barang', 'diskon'));
    }

    public function addItem(Request $request)
    {
        // Ambil stok barang yang tersedia dan belum kadaluarsa
        $barangStokList = BarangStok::where('barang_id', $request->id_barang)
            ->where('jumlah_stok', '>', 0)
            ->where('tanggal_kedaluarsa', '>=', now()) // Pastikan stok belum kadaluarsa
            ->orderBy('tanggal_kedaluarsa', 'asc') // Urutkan berdasarkan tanggal kedaluarsa
            ->get();
    
        // Jika tidak ada stok yang valid, berikan error barang kadaluarsa
        if ($barangStokList->isEmpty()) {
            return response()->json([
                'error' => 'Semua stok barang ini sudah kedaluwarsa dan tidak bisa dijual.'
            ], 400);
        }
    
        // Hitung total stok yang tersedia (hanya stok yang belum kedaluwarsa)
        $totalStokTersedia = $barangStokList->sum('jumlah_stok');
        $stokYangBisaDitransaksikan = min($totalStokTersedia, 10); // Maksimal 10 unit
    
        if ($request->quantity > $stokYangBisaDitransaksikan) {
            return response()->json([
                'error' => 'Stok barang tidak mencukupi! Stok yang dapat diproses hanya ' . $stokYangBisaDitransaksikan . ' unit.'
            ], 400);
        }
    
        // Ambil informasi barang
        $barang = Barang::findOrFail($request->id_barang);
        $harga_jual = $barang->harga_jual;
        $hpp = $barang->hpp; // Ambil HPP dari barang, bisa disesuaikan jika HPP ada di database.
    
        // Cek tipe pelanggan dan sesuaikan harga
        $user = User::find($request->id_pengguna);
        if ($user) {
            switch ($user->tipe_pelanggan) {
                case '1':
                    $harga_jual += $harga_jual * 0.10; // Menambah 10% harga
                    break;
                case '2':
                    $harga_jual += $harga_jual * 0.20; // Menambah 20% harga
                    break;
                case '3':
                    $harga_jual += $harga_jual * 0.30; // Menambah 30% harga
                    break;
            }
        } else {
            // Jika tidak ada pengguna, gunakan harga tipe 3 sebagai default
            $harga_jual += $harga_jual * 0.30; // Menambah 30% harga
        }
    
        // Lakukan pengurangan stok berdasarkan kedaluwarsa
        $sisaQuantity = $request->quantity;
        $totalHarga = 0;
        
        foreach ($barangStokList as $stok) {
            if ($sisaQuantity <= 0) break;
    
            $stokSebelum = $stok->jumlah_stok;
            if ($stok->jumlah_stok >= $sisaQuantity) {
                $stok->decrement('jumlah_stok', $sisaQuantity);
                $totalHarga += $harga_jual * $sisaQuantity; // Tambahkan total harga berdasarkan quantity
                $sisaQuantity = 0;
            } else {
                $sisaQuantity -= $stok->jumlah_stok;
                $stok->update(['jumlah_stok' => 0]);
                $totalHarga += $harga_jual * $stokSebelum; // Tambahkan harga berdasarkan stok yang tersedia
            }
    
            // Log perubahan stok
            Log::info('Pengurangan stok:', [
                'barang_id' => $request->id_barang,
                'stok_sebelum' => $stokSebelum,
                'stok_dikurangi' => min($stokSebelum, $request->quantity),
                'stok_setelah' => max(0, $stokSebelum - $request->quantity),
            ]);
        }
    
        // Hitung subtotal transaksi
        $subtotal = $totalHarga;
    
        // Kembalikan response dengan data yang sesuai
        return response()->json([
            'id_barang' => $barang->id,
            'nama_barang' => $barang->nama_barang,
            'harga_jual' => $harga_jual,
            'hpp' => $hpp, // HPP yang digunakan
            'quantity' => $request->quantity,
            'subtotal' => $subtotal,
            'stock_barang' => $stokYangBisaDitransaksikan,
            'tanggal_kedaluarsa_terdekat' => $barangStokList->first()->tanggal_kedaluarsa ?? null
        ]);
    }
    
    
    
    
    




    public function checkout(Request $request)
    {
        \Log::info('Data yang diterima:', $request->all());

        // Validasi input
        $request->validate([
            'id_pengguna' => 'nullable|exists:users,id', // Tidak wajib memilih pengguna
            'id_diskon' => 'nullable|exists:diskon,id',
            'uang_masuk' => 'required|numeric',
            'used_poin' => 'nullable|numeric|min:0',
            'items' => 'required|array',
            'items.*.id_barang' => 'required|exists:barang,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        if (!is_array($request->items) || empty($request->items)) {
            return response()->json(['error' => 'Daftar barang tidak valid atau kosong.'], 422);
        }

        try {
            DB::beginTransaction(); // Memulai transaksi database
            
            Log::info('Mulai transaksi checkout', ['request' => $request->all()]);
        
            // Ambil data pengguna (jika ada)
            $pengguna = $request->id_pengguna ? User::find($request->id_pengguna) : null;
            $tipePelanggan = $pengguna ? $pengguna->tipe_pelanggan : 3;
            $totalBelanja = 0;
            $itemsData = [];
            $diskon = 0;
            $potonganDiskon = 0;
            
            foreach ($request->items as $item) {
                $barang = Barang::findOrFail($item['id_barang']);
                
                // Ambil stok barang yang tersedia dan belum kadaluarsa
                $barangStokList = BarangStok::where('barang_id', $item['id_barang'])
                    ->where('jumlah_stok', '>', 0)
                    ->where('tanggal_kedaluarsa', '>=', now())
                    ->orderBy('tanggal_kedaluarsa', 'asc')
                    ->get();
        
                $totalStokTersedia = $barangStokList->sum('jumlah_stok');
                
                if ($item['quantity'] > $totalStokTersedia) {
                    throw new Exception("Stok tidak mencukupi untuk barang {$barang->nama_barang}!");
                }
        
                $sisaQuantity = $item['quantity'];
                foreach ($barangStokList as $stok) {
                    if ($sisaQuantity <= 0) break;
        
                    $stokSebelum = $stok->jumlah_stok;
                    $dikurangi = min($stok->jumlah_stok, $sisaQuantity);
                    $stok->decrement('jumlah_stok', $dikurangi);
                    $sisaQuantity -= $dikurangi;
        
                    Log::info('Pengurangan stok:', [
                        'barang_id' => $barang->id,
                        'stok_sebelum' => $stokSebelum,
                        'stok_dikurangi' => $dikurangi,
                        'stok_setelah' => $stokSebelum - $dikurangi,
                    ]);
                }
        
                // Hitung harga jual berdasarkan tipe pelanggan
                $harga_jual = $barang->harga_jual + ($barang->hpp ?? 0);
                $markup = [1 => 0.10, 2 => 0.20, 3 => 0.30][$tipePelanggan] ?? 0.30;
                $harga_jual += $harga_jual * $markup;
                
                // Hitung subtotal
                $subtotal = $harga_jual * $item['quantity'];
                $totalBelanja += $subtotal;
                
                $itemsData[] = [
                    'barang' => $barang,
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal,
                    'nama_barang' => $barang->nama_barang
                ];
            }
            
            // Cek dan hitung diskon
            if ($pengguna && !empty($request->id_diskon)) {
                $diskonData = Diskon::where('id', $request->id_diskon)->where('status', 1)->first();
                if ($diskonData && $totalBelanja >= $diskonData->min_pembelanjaan) {
                    $diskon = $diskonData->diskon_persen;
                    $potonganDiskon = ($totalBelanja * $diskon) / 100;
                }
            }
        
            // Total setelah diskon
            $totalSetelahDiskon = max($totalBelanja - $potonganDiskon, 0);
            
            // Gunakan poin jika ada
            $usedPoin = $pengguna ? min(intval($request->poin_digunakan ?? 0), $pengguna->membership_poin, intval($totalSetelahDiskon * 0.50)) : 0;
            $totalSetelahPoinDanDiskon = max($totalSetelahDiskon - $usedPoin, 0);
        
            // Hitung PPN 12%
            $ppn = round($totalSetelahPoinDanDiskon * 0.12, 2);
            $totalAkhir = round($totalSetelahPoinDanDiskon + $ppn, 2);
        
            // **Tambahkan log debugging**
            Log::info('Detail Perhitungan:', [
                'Total Belanja' => $totalBelanja,
                'Diskon (%)' => $diskon,
                'Potongan Diskon' => $potonganDiskon,
                'Total Setelah Diskon' => $totalSetelahDiskon,
                'Poin Digunakan' => $usedPoin,
                'Total Setelah Poin & Diskon' => $totalSetelahPoinDanDiskon,
                'PPN' => $ppn,
                'Total Akhir' => $totalAkhir,
                'Uang Masuk' => $request->uang_masuk,
            ]);
        
            // Validasi uang masuk
            $uangMasuk = is_numeric($request->uang_masuk) ? floatval($request->uang_masuk) : 0;
            $uangKembalian = round($uangMasuk - $totalAkhir, 2);
            if ($uangKembalian < 0) {
                throw new Exception('Uang yang diberikan kurang!');
            }
        
            // Kurangi poin pengguna
            if ($usedPoin > 0 && $pengguna) {
                $pengguna->decrement('membership_poin', $usedPoin);
            }
        
            // Hitung poin yang didapat
            $poinDidapat = ($pengguna && in_array($pengguna->tipe_pelanggan, [1, 2])) ? floor($totalBelanja * 0.02) : 0;
            if ($poinDidapat > 0 && $pengguna) {
                $pengguna->increment('membership_poin', $poinDidapat);
            }
        
            // Simpan transaksi
            $penjualan = Penjualan::create([
                'id_pengguna' => $pengguna?->id,
                'id_kasir' => auth()->id(),
                'id_diskon' => $pengguna ? $request->id_diskon : null,
                'diskon_persen' => $diskon,
                'total_pembelanjaan' => $totalBelanja,
                'used_poin' => $usedPoin,
                'total_akhir' => $totalAkhir,
                'uang_masuk' => $uangMasuk,
                'uang_kembalian' => $uangKembalian,
                'poin_didapat' => $poinDidapat,
                'tipe_pengguna_transaksi' => $tipePelanggan,
                'nama_pembeli' => $pengguna?->name ?? 'Umum',
                'nama_kasir' => auth()->user()->name,
            ]);
        
            // Simpan detail transaksi
            foreach ($itemsData as $item) {
                DetailPenjualan::create([
                    'id_penjualan' => $penjualan->id,
                    'id_barang' => $item['barang']->id,
                    'quantity' => $item['quantity'],
                    'nama_barang' => $item['nama_barang'],
                    'subtotal_harga' => $item['subtotal'] - ($item['subtotal'] * $diskon / 100),
                ]);
            }
        
            DB::commit();
            return response()->json(['message' => 'Transaksi berhasil!', 'redirect_url' => route('laporan.show', $penjualan->id)]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Checkout Error', ['message' => $e->getMessage(), 'stack' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Terjadi kesalahan saat menyimpan transaksi.'], 500);
        }
        
        
        
        
        
        
        
    }
}
