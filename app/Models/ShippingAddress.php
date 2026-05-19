<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ShippingAddress extends Model
{
    use HasUuids;

    protected $fillable = [
        'pengguna_id',
        'label',
        'nama_penerima',
        'telepon',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'utama',
    ];

    protected $casts = [
        'utama' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
