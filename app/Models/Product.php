<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Product extends Model
{
    use HasUuids;

    protected $fillable = [
        'kategori_id',
        'nama',
        'slug',
        'deskripsi',
        'deskripsi_singkat',
        'gambar',
        'harga_dasar',
        'ukuran',
        'bahan',
        'sisi_cetak',
        'finishing',
        'tier_jumlah',
        'terlaris',
        'promo',
        'persen_promo',
        'min_pesan',
        'estimasi_hari',
        'berat_per_pcs',
        'produk_retail',
        'butuh_file_desain',
        'tipe_file_diperbolehkan',
        'ukuran_file_maks',
        'aktif',
    ];

    protected $casts = [
        'gambar' => 'array',
        'ukuran' => 'array',
        'bahan' => 'array',
        'sisi_cetak' => 'array',
        'finishing' => 'array',
        'tier_jumlah' => 'array',
        'tipe_file_diperbolehkan' => 'array',
        'terlaris' => 'boolean',
        'promo' => 'boolean',
        'produk_retail' => 'boolean',
        'butuh_file_desain' => 'boolean',
        'aktif' => 'boolean',
        'harga_dasar' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    public function designTemplates()
    {
        return $this->hasMany(ProductDesignTemplate::class, 'produk_id')->orderBy('urutan');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'produk_id');
    }
}
