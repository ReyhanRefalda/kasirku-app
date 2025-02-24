<?php

namespace App\Http\Controllers;

use App\Models\Diskon;
use Illuminate\Http\Request;

class DiskonController extends Controller
{
    /**
     * Tampilkan daftar diskon.
     */
    public function index()
    {
        $diskon = Diskon::withTrashed()
            ->orderByRaw('CASE WHEN deleted_at IS NULL THEN 0 ELSE 1 END')
            ->get(); 
    
        return view('diskon.index', compact('diskon'));
    }

    /**
     * Tampilkan form tambah diskon.
     */
    public function create()
    {
        return view('diskon.create');
    }

    /**
     * Simpan data diskon ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kode_diskon' => 'required|unique:diskon,kode_diskon|max:50',
            'nama_diskon' => 'required|max:100',
            'diskon_persen' => 'required|numeric|min:1|max:100',
            'min_pembelanjaan' => 'required|string|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
        ], [
            'kode_diskon.required' => 'Kode Diskon wajib diisi.',
            'kode_diskon.unique' => 'Kode Diskon sudah digunakan, pilih yang lain.',
            'kode_diskon.max' => 'Kode Diskon maksimal 50 karakter.',
            
            'nama_diskon.required' => 'Nama Diskon wajib diisi.',
            'nama_diskon.max' => 'Nama Diskon maksimal 100 karakter.',
            
            'diskon_persen.required' => 'Persentase Diskon wajib diisi.',
            'diskon_persen.numeric' => 'Persentase Diskon harus berupa angka.',
            'diskon_persen.min' => 'Persentase Diskon minimal 1%.',
            'diskon_persen.max' => 'Persentase Diskon maksimal 100%.',
            
            'min_pembelanjaan.required' => 'Minimal pembelanjaan wajib diisi.',
            'min_pembelanjaan.string' => 'Minimal pembelanjaan harus berupa angka.',
            'min_pembelanjaan.min' => 'Minimal pembelanjaan tidak boleh kurang dari 0.',
    
            'tanggal_mulai.required' => 'Tanggal Mulai wajib diisi.',
            'tanggal_mulai.date' => 'Tanggal Mulai harus berupa tanggal yang valid.',
            
            'tanggal_berakhir.required' => 'Tanggal Berakhir wajib diisi.',
            'tanggal_berakhir.date' => 'Tanggal Berakhir harus berupa tanggal yang valid.',
            'tanggal_berakhir.after_or_equal' => 'Tanggal Berakhir harus setelah atau sama dengan Tanggal Mulai.',
        ]);
    
        // Hapus titik sebelum menyimpan
        $request->merge([
            'min_pembelanjaan' => str_replace('.', '', $request->min_pembelanjaan)
        ]);
    
        Diskon::create($request->all());
    
        return redirect()->route('diskon.index')->with('success', 'Diskon berhasil ditambahkan.');
    }
    


    /**
     * Tampilkan form edit diskon.
     */
    public function edit(Diskon $diskon)
    {
        return view('diskon.edit', compact('diskon'));
    }

    /**
     * Update data diskon.
     */
    public function update(Request $request, $id) 
    {
        // Ambil data diskon berdasarkan ID
        $diskon = Diskon::findOrFail($id);
    
        // Validasi input dengan pesan kustom
        $request->validate([
            'kode_diskon' => 'required|max:50|unique:diskon,kode_diskon,' . $id,
            'nama_diskon' => 'required|max:100',
            'diskon_persen' => 'required|numeric|min:1|max:100',
            'min_pembelanjaan' => 'required|string|min:0',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'required|date|after_or_equal:tanggal_mulai',
        ], [
            'kode_diskon.required' => 'Kode Diskon wajib diisi.',
            'kode_diskon.unique' => 'Kode Diskon sudah digunakan, pilih yang lain.',
            'kode_diskon.max' => 'Kode Diskon maksimal 50 karakter.',
            
            'nama_diskon.required' => 'Nama Diskon wajib diisi.',
            'nama_diskon.max' => 'Nama Diskon maksimal 100 karakter.',
            
            'diskon_persen.required' => 'Persentase Diskon wajib diisi.',
            'diskon_persen.numeric' => 'Persentase Diskon harus berupa angka.',
            'diskon_persen.min' => 'Persentase Diskon minimal 1%.',
            'diskon_persen.max' => 'Persentase Diskon maksimal 100%.',
            
            'min_pembelanjaan.required' => 'Minimal pembelanjaan wajib diisi.',
            'min_pembelanjaan.string' => 'Minimal pembelanjaan harus berupa angka.',
            'min_pembelanjaan.min' => 'Minimal pembelanjaan tidak boleh kurang dari 0.',
    
            'tanggal_mulai.required' => 'Tanggal Mulai wajib diisi.',
            'tanggal_mulai.date' => 'Tanggal Mulai harus berupa tanggal yang valid.',
            
            'tanggal_berakhir.required' => 'Tanggal Berakhir wajib diisi.',
            'tanggal_berakhir.date' => 'Tanggal Berakhir harus berupa tanggal yang valid.',
            'tanggal_berakhir.after_or_equal' => 'Tanggal Berakhir harus setelah atau sama dengan Tanggal Mulai.',
        ]);
    
        // Hapus titik sebelum menyimpan
        $request->merge([
            'min_pembelanjaan' => str_replace('.', '', $request->min_pembelanjaan)
        ]);
    
        // Update data diskon
        $diskon->update($request->all());
    
        return redirect()->route('diskon.index')->with('success', 'Diskon berhasil diperbarui.');
    }
    

    /**
     * Hapus data diskon.
     */
    public function destroy(Diskon $diskon)
    {
        $diskon->delete();
        return redirect()->route('diskon.index')->with('success', 'Diskon berhasil dihapus.');
    }

    public function restore($id)
    {
        $diskon = Diskon::withTrashed()->findOrFail($id);
        $diskon->restore();

        return redirect()->route('diskon.index')->with('success', 'Diskon berhasil dikembalikan.');
    }
}
