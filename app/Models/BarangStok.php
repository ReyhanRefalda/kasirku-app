<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class BarangStok extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'barang_stok';

    protected $fillable = [
        'kode_barang',
        'barang_id',
        'jumlah_stok',
        'tanggal_pembelian',
        'tanggal_kedaluarsa'
    ];

    protected $casts = [
        'tanggal_pembelian' => 'date',
        'tanggal_kedaluarsa' => 'date',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    // Konfigurasi Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('stok_barang') // Ubah dari "barang_stok" ke "stok_barang"
            ->setDescriptionForEvent(fn(string $eventName) => "Stok barang telah {$this->translateEvent($eventName)} oleh " . (auth()->user()->name ?? 'Sistem'));
    }

    // Fungsi tambahan untuk menerjemahkan event ke bahasa Indonesia
    protected function translateEvent($eventName)
    {
        return match ($eventName) {
            'created' => 'ditambahkan',
            'updated' => 'diperbarui',
            'deleted' => 'dihapus',
            default => $eventName,
        };
    }
}
