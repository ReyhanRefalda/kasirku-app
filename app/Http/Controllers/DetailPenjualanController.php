<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use App\Models\Barang;

class DetailPenjualanController extends Controller
{
    /**
     * Menampilkan daftar detail penjualan (history transaksi).
     */
    public function index()
    {
        $detailPenjualan = DetailPenjualan::with(['penjualan', 'barang'])->latest()->get();
        return view('detail_penjualan.index', compact('detailPenjualan'));
    }

    /**
     * Menyimpan detail penjualan ke database (tanpa edit).
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_penjualan' => 'required|exists:penjualan,id',
            'id_barang' => 'required|exists:barang,id',
            'quantity' => 'required|integer|min:1',
            'subtotal_harga' => 'required|integer|min:0',
        ]);

        DetailPenjualan::create([
            'id_penjualan' => $request->id_penjualan,
            'id_barang' => $request->id_barang,
            'quantity' => $request->quantity,
            'subtotal_harga' => $request->subtotal_harga,
            'nama_barang' => $request->nama_barang,
        ]);

        return redirect()->route('detail-penjualan.index')->with('success', 'Transaksi berhasil disimpan!');
    }
}
