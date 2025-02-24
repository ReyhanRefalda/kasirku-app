<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Kategori extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'kategori';

    protected static $logAttributes = ['nama', 'kode'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'Kategori';
    protected static $recordEvents = ['created', 'updated', 'deleted']; 

    protected $fillable = ['nama', 'kode'];

    public function barang()
    {
        return $this->hasMany(Barang::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('kategori')
            ->setDescriptionForEvent(fn(string $eventName) => "Kategori telah {$eventName} oleh ".auth()->user()->name);
    }
}
