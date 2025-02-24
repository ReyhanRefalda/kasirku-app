<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Carbon;

class Barang extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'barang';

    protected static $logAttributes = ['nama_barang', 'harga_jual'];
    protected static $logOnlyDirty = true; // Hanya mencatat perubahan
    protected static $logName = 'Barang';

    protected static $recordEvents = ['created', 'updated', 'deleted']; 
    protected $fillable = [
        'kode_barang', 
        'nama_barang', 
        // 'tanggal_kedaluarsa', 
        // 'tanggal_pembelian', 
        'kategori_id', 
        'harga_jual', 
        // 'stock_barang',
        'minimal_stok' ,
        'hpp_tipe1', 'hpp_tipe2', 'hpp_tipe3'
    ];

    protected $dates = ['tanggal_kedaluarsa']; // Konversi otomatis ke Carbon

    // public function getTanggalKedaluarsaAttribute($value)
    // {
    //     return Carbon::parse($value);
    // }

    public function stok()
    {
        return $this->hasMany(BarangStok::class, 'barang_id');
    }

    // Menghitung total stok dari semua entri di BarangStok
    public function getTotalStokAttribute()
    {
        return $this->stok->sum('jumlah_stok');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    // Konfigurasi Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Mencatat semua perubahan
            ->useLogName('barang') // Nama log
            ->setDescriptionForEvent(fn(string $eventName) => "Barang telah {$eventName} oleh ".auth()->user()->name);
    }
}
