<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kategori;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::withTrashed()
            ->orderByRaw('CASE WHEN deleted_at IS NULL THEN 0 ELSE 1 END') 
            ->paginate(10); 
    
        return view('kategori.index', compact('kategori'));
    }
    
    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:kategori,kode',
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.string' => 'Nama kategori harus berupa teks.',
            'nama.max' => 'Nama kategori tidak boleh lebih dari 255 karakter.',
            'kode.required' => 'Kode kategori wajib diisi.',
            'kode.string' => 'Kode kategori harus berupa teks.',
            'kode.max' => 'Kode kategori tidak boleh lebih dari 50 karakter.',
            'kode.unique' => 'Kode kategori sudah digunakan, gunakan kode lain.',
        ]);
    
        Kategori::create($request->all());
    
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }
    
    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id); // Pastikan data kategori ditemukan
    
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50|unique:kategori,kode,' . $id,
        ], [
            'nama.required' => 'Nama kategori wajib diisi.',
            'nama.string' => 'Nama kategori harus berupa teks.',
            'nama.max' => 'Nama kategori tidak boleh lebih dari 255 karakter.',
            'kode.required' => 'Kode kategori wajib diisi.',
            'kode.string' => 'Kode kategori harus berupa teks.',
            'kode.max' => 'Kode kategori tidak boleh lebih dari 50 karakter.',
            'kode.unique' => 'Kode kategori sudah digunakan, gunakan kode lain.',
        ]);
    
        $kategori->update($request->all());
    
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }
    

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }

    public function restore($id)
    {
        $kategori = Kategori::withTrashed()->findOrFail($id);
        $kategori->restore();
        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dikembalikan.');
    }
}
