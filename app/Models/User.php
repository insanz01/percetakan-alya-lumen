<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, HasUuids;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'telepon',
        'foto_profil',
        'peran',
        'aktif',
    ];

    protected $hidden = [
        'password',
        'token_ingat_saya',
    ];

    protected $casts = [
        'email_diverifikasi_pada' => 'datetime',
        'aktif' => 'boolean',
    ];

    public function addresses()
    {
        return $this->hasMany(ShippingAddress::class, 'pengguna_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'pengguna_id');
    }

    public function isAdmin()
    {
        return in_array($this->peran, ['admin', 'super_admin']);
    }
}
