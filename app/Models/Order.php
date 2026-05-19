<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Order extends Model
{
    use HasUuids;

    protected $fillable = [
        'nomor_pesanan',
        'pengguna_id',
        'alamat_pengiriman_id',
        'metode_pengiriman',
        'kurir',
        'nomor_resi',
        'metode_pembayaran',
        'tipe_pembayaran',
        'subtotal',
        'biaya_kirim',
        'diskon',
        'total',
        'status',
        'status_bayar',
        'batas_bayar',
        'dibayar_pada',
        'catatan',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'biaya_kirim' => 'decimal:2',
        'diskon' => 'decimal:2',
        'total' => 'decimal:2',
        'batas_bayar' => 'datetime',
        'dibayar_pada' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class);
    }

    public static function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = Carbon::now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -4));
        return "{$prefix}-{$date}-{$random}";
    }
}
