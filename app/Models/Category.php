<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Category extends Model
{
    use HasUuids;

    protected $fillable = [
        'nama',
        'slug',
        'ikon',
        'deskripsi',
        'gambar',
        'aktif',
        'urutan',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    protected $appends = ['product_count'];

    public function products()
    {
        return $this->hasMany(Product::class, 'kategori_id');
    }

    public function getProductCountAttribute()
    {
        return $this->products()->count();
    }
}
