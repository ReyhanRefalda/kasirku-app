<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_penjualan';

    protected $fillable = [
        'id_penjualan',
        'id_barang',
        'quantity',
        'subtotal_harga',
        'nama_barang',
    ];

    // Relasi ke tabel penjualan
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'id_penjualan');
    }

    // Relasi ke tabel barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
