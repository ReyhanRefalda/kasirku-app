<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable , SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'membership_poin', // Ubah dari 'poin' ke 'membership_poin'
        'tipe_pelanggan',
    ];
    

    public function tambahPoin($jumlah)
    {
        $this->increment('membership_poin', $jumlah);
    }
    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Setter: Hanya pelanggan yang bisa memiliki tipe pelanggan
     */
    public function setTipePelangganAttribute($value)
    {
        if ($this->attributes['role'] === 'pengguna') { // Ganti pelanggan -> pengguna
            $this->attributes['tipe_pelanggan'] = in_array($value, ['1', '2', '3']) ? $value : null;
        } else {
            $this->attributes['tipe_pelanggan'] = null;
        }
    }
    
}
