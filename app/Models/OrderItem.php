<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class OrderItem extends Model
{
    use HasUuids;

    protected $fillable = [
        'pesanan_id',
        'produk_id',
        'ukuran_id',
        'nama_ukuran',
        'bahan_id',
        'nama_bahan',
        'sisi_cetak_id',
        'nama_sisi_cetak',
        'finishing_ids',
        'nama_finishing',
        'lebar_kustom',
        'tinggi_kustom',
        'jumlah',
        'harga_satuan',
        'harga_total',
        'nama_file_diunggah',
        'tautan_file_diunggah',
        'status_file_diunggah',
        'status',
        'catatan',
    ];

    protected $casts = [
        'finishing_ids' => 'array',
        'nama_finishing' => 'array',
        'harga_satuan' => 'decimal:2',
        'harga_total' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'pesanan_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'produk_id');
    }
}
