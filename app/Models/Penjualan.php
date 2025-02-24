<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Penjualan extends Model
{
    use HasFactory, LogsActivity;

    protected static $logAttributes = ['total_pembelanjaan', 'total_akhir', 'uang_masuk'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'Penjualan';

    protected static $recordEvents = ['created', 'updated', 'deleted']; 

    protected $table = 'penjualan';

    protected $fillable = [
        'id_pengguna',
        'id_kasir',
        'id_diskon',
        'diskon_persen',
        'total_pembelanjaan',
        'nominal_diskon',
        'used_poin',
        'total_akhir',
        'uang_masuk',
        'uang_kembalian',
        'poin_didapat',
        'tipe_pengguna_transaksi', 
        'nama_pembeli', 
        'nama_kasir',
    ];

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'id_kasir');
    }

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'id_penjualan');
    }

    public function diskon()
    {
        return $this->belongsTo(Diskon::class, 'id_diskon');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('penjualan')
            ->setDescriptionForEvent(fn(string $eventName) => "Penjualan telah {$eventName} oleh ".auth()->user()->name);
    }
}
