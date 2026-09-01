<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProductDesignTemplate extends Model
{
    use HasUuids;

    protected $fillable = [
        'produk_id',
        'nama',
        'gambar',
        'urutan',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'produk_id');
    }
}
