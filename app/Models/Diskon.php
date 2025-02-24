<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Activity;

class Diskon extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'diskon';

    protected static $logAttributes = [
        'kode_diskon', 'nama_diskon', 'diskon_persen', 
        'min_pembelanjaan', 'tanggal_mulai', 'tanggal_berakhir'
    ];
    protected static $logOnlyDirty = true; // Hanya mencatat perubahan
    protected static $logName = 'Diskon';
    protected static $recordEvents = ['created', 'updated', 'deleted'];

    protected $fillable = [
        'kode_diskon', 
        'nama_diskon', 
        'diskon_persen', 
        'min_pembelanjaan', 
        'tanggal_mulai', 
        'tanggal_berakhir'
    ];

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'id_diskon');
    }

    public function getStatusAttribute()
    {
        $today = now();
        return $today->between($this->tanggal_mulai, $this->tanggal_berakhir);
    }

    // Konfigurasi Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('Diskon')
            ->setDescriptionForEvent(fn(string $eventName) => "Diskon telah {$eventName} oleh ".auth()->user()->name);
    }

    // Menyimpan data sebelum dihapus
    public function tapActivity(Activity $activity, string $eventName)
    {
        if ($eventName === 'deleted') {
            $activity->properties = collect([
                'old' => $this->getOriginal(), // Simpan semua data sebelum dihapus
            ]);
        }
    }
}
