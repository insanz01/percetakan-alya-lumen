<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Promo extends Model
{
    use HasUuids;

    protected $fillable = [
        'kode',
        'deskripsi',
        'tipe',
        'diskon',
        'min_beli',
        'maks_diskon',
        'batas_penggunaan',
        'jumlah_penggunaan',
        'tanggal_mulai',
        'tanggal_berakhir',
        'aktif',
    ];

    protected $casts = [
        'diskon' => 'decimal:2',
        'min_beli' => 'decimal:2',
        'maks_diskon' => 'decimal:2',
        'tanggal_mulai' => 'datetime',
        'tanggal_berakhir' => 'datetime',
        'aktif' => 'boolean',
    ];

    public function isValid()
    {
        $now = Carbon::now();

        if (!$this->aktif) {
            return false;
        }

        if ($this->tanggal_mulai && $now->lt($this->tanggal_mulai)) {
            return false;
        }

        if ($this->tanggal_berakhir && $now->gt($this->tanggal_berakhir)) {
            return false;
        }

        if ($this->batas_penggunaan && $this->jumlah_penggunaan >= $this->batas_penggunaan) {
            return false;
        }

        return true;
    }

    public function calculateDiscount($amount)
    {
        if ($amount < $this->min_beli) {
            return 0;
        }

        if ($this->tipe === 'percentage') {
            $discount = $amount * ($this->diskon / 100);
        } else {
            $discount = $this->diskon;
        }

        if ($this->maks_diskon && $discount > $this->maks_diskon) {
            $discount = $this->maks_diskon;
        }

        return $discount;
    }
}
