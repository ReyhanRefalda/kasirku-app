<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\BarangStok;
use Illuminate\Http\Request;
use App\Exports\BarangExport;
use Maatwebsite\Excel\Facades\Excel;


class BarangController extends Controller
{
    public function index()
{
    $barang = Barang::withTrashed()
        ->orderByRaw('CASE WHEN deleted_at IS NULL THEN 0 ELSE 1 END') // Menempatkan yang terhapus di bawah
        ->paginate(10);

    return view('barang.index', compact('barang'));
}
    
    public function detail($id)
{
    $barang = Barang::with('stok')->findOrFail($id);
    return view('barang.detail', compact('barang'));
}


   
    public function tambahStokForm($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.tambah_stok', compact('barang'));
    }

    // Proses menambah stok barang
    public function tambahStok(Request $request, $id)
{
    $request->validate([
        'jumlah_stok' => 'required|integer|min:1',
        'tanggal_pembelian' => 'required|date',
        'tanggal_kedaluarsa' => 'required|date|after:tanggal_pembelian',
    ], [
        'jumlah_stok.required' => 'Jumlah stok harus diisi.',
        'jumlah_stok.integer' => 'Jumlah stok harus berupa angka.',
        'jumlah_stok.min' => 'Jumlah stok minimal 1.',
        'tanggal_pembelian.required' => 'Tanggal pembelian harus diisi.',
        'tanggal_pembelian.date' => 'Tanggal pembelian tidak valid.',
        'tanggal_kedaluarsa.required' => 'Tanggal kedaluwarsa harus diisi.',
        'tanggal_kedaluarsa.date' => 'Tanggal kedaluwarsa tidak valid.',
        'tanggal_kedaluarsa.after' => 'Tanggal kedaluwarsa harus setelah tanggal pembelian.',
    ]);

    $barang = Barang::findOrFail($id);

    BarangStok::create([
        'kode_barang' => $barang->kode_barang,
        'barang_id' => $barang->id,
        'jumlah_stok' => $request->jumlah_stok,
        'tanggal_pembelian' => $request->tanggal_pembelian,
        'tanggal_kedaluarsa' => $request->tanggal_kedaluarsa,
    ]);

    return redirect()->route('barang.index')->with('success', 'Stok berhasil ditambahkan!');
}

   

    public function create()
    {
        $kategori = Kategori::all(); // Ambil semua data kategori
        return view('barang.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        // Menghapus format mata uang sebelum validasi
        $request->merge([
            'harga_jual' => preg_replace('/[^\d]/', '', $request->harga_jual),
        ]);
    
        $request->validate([
            'kode_barang' => 'required|unique:barang',
            'nama_barang' => 'required',
            // 'tanggal_kedaluarsa' => 'required|date',
            // 'tanggal_pembelian' => 'required|date',
            'harga_jual' => 'required|numeric|min:0',
            // 'stock_barang' => 'required|integer|min:0',
            'minimal_stok' => 'required|integer|min:0',
        ], [
            'kode_barang.required' => 'Kode barang harus diisi.',
            'kode_barang.unique' => 'Kode barang sudah digunakan.',
            'nama_barang.required' => 'Nama barang harus diisi.',
            'tanggal_kedaluarsa.required' => 'Tanggal kedaluwarsa harus diisi.',
            // 'tanggal_kedaluarsa.date' => 'Format tanggal kedaluwarsa tidak valid.',
            // 'tanggal_pembelian.required' => 'Tanggal pembelian harus diisi.',
            // 'tanggal_pembelian.date' => 'Format tanggal pembelian tidak valid.',
            'harga_jual.required' => 'Harga jual harus diisi.',
            'harga_jual.numeric' => 'Harga jual harus berupa angka.',
            'harga_jual.min' => 'Harga jual tidak boleh kurang dari 0.',
            // 'stock_barang.required' => 'Stok barang harus diisi.',
            // 'stock_barang.integer' => 'Stok barang harus berupa angka.',
            // 'stock_barang.min' => 'Stok barang tidak boleh kurang dari 0.',
            'minimal_stok.required' => 'Minimal stok harus diisi.',
            'minimal_stok.integer' => 'Minimal stok harus berupa angka.',
            'minimal_stok.min' => 'Minimal stok tidak boleh kurang dari 0.',
        ]);
        
        $hargaJual = $request->harga_jual;
    
        Barang::create([
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            // 'tanggal_kedaluarsa' => $request->tanggal_kedaluarsa,
            // 'tanggal_pembelian' => $request->tanggal_pembelian,
            'kategori_id' => $request->kategori_id,
            'harga_jual' => $request->harga_jual, // Sudah bersih dari format mata uang
            // 'stock_barang' => $request->stock_barang,
          'minimal_stok' => $request->minimal_stok,
          'hpp_tipe1' => $hargaJual + ($hargaJual * 0.10),
          'hpp_tipe2' => $hargaJual + ($hargaJual * 0.20),
          'hpp_tipe3' => $hargaJual + ($hargaJual * 0.30),

        ]);
    
        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }
    
    

    public function edit(Barang $barang)
    {
        $kategori = Kategori::all();
        return view('barang.edit', compact('barang', 'kategori'));
    }

    public function laporan()
    {
        // Ambil semua barang beserta jumlah stok yang ada pada barang_stok
        $barang = Barang::withSum('stok', 'jumlah_stok')->get();
    
        return view('barang.laporan', compact('barang'));
    }
    
    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);

        $request->validate([
            'kode_barang' => 'required|unique:barang,kode_barang,' . $barang->id,
            'nama_barang' => 'required',
            // 'tanggal_kedaluarsa' => 'required|date',
            // 'tanggal_pembelian' => 'required|date',
            'harga_jual' => 'required|numeric|min:0',
            // 'stock_barang' => 'required|integer|min:0',
            'minimal_stok' => 'required|integer|min:0',
        ], [
            'kode_barang.required' => 'Kode barang wajib diisi.',
            'kode_barang.unique' => 'Kode barang sudah terdaftar. Gunakan kode lain.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            // 'tanggal_kedaluarsa.required' => 'Tanggal kedaluwarsa wajib diisi.',
            // 'tanggal_kedaluarsa.date' => 'Format tanggal kedaluwarsa tidak valid.',
            // 'tanggal_pembelian.required' => 'Tanggal pembelian wajib diisi.',
            // 'tanggal_pembelian.date' => 'Format tanggal pembelian tidak valid.',
            'harga_jual.required' => 'Harga jual wajib diisi.',
            'harga_jual.numeric' => 'Harga jual harus berupa angka.',
            'harga_jual.min' => 'Harga jual tidak boleh negatif.',
            // 'stock_barang.required' => 'Stok barang wajib diisi.',
            // 'stock_barang.integer' => 'Stok barang harus berupa angka bulat.',
            // 'stock_barang.min' => 'Stok barang tidak boleh negatif.',
            'minimal_stok.required' => 'Stok barang harus diisi.',
        ]);
    
        $hargaJual = $request->harga_jual;
        $barang->update([
            'kode_barang' => $request->kode_barang,
            'nama_barang' => $request->nama_barang,
            // 'tanggal_kedaluarsa' => $request->tanggal_kedaluarsa,
            // 'tanggal_pembelian' => $request->tanggal_pembelian,
            'kategori_id' => $request->kategori_id,
            'harga_jual' => $request->harga_jual,
            // 'stock_barang' => $request->stock_barang,
            'minimal_stok' => $request->minimal_stok,
            'hpp_tipe1' => $hargaJual + ($hargaJual * 0.10),
            'hpp_tipe2' => $hargaJual + ($hargaJual * 0.20),
            'hpp_tipe3' => $hargaJual + ($hargaJual * 0.30),
        ]);
    
        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }
    

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }

    public function restore($id)
    {
        $barang = Barang::withTrashed()->findOrFail($id);
        $barang->restore();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dikembalikan.');
    }

    public function export()
    {
        return Excel::download(new BarangExport, 'laporan_stok.xlsx');
    }
}
