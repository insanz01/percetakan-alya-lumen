<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    use HasUuids;

    protected $fillable = [
        'email',
        'aktif',
        'token_berhenti',
        'berlangganan_pada',
        'berhenti_langganan_pada',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'berlangganan_pada' => 'datetime',
        'berhenti_langganan_pada' => 'datetime',
    ];

    /**
     * Inisialisasi model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subscriber) {
            if (empty($subscriber->token_berhenti)) {
                $subscriber->token_berhenti = Str::random(64);
            }
            if (empty($subscriber->berlangganan_pada)) {
                $subscriber->berlangganan_pada = Carbon::now();
            }
        });
    }

    /**
     * Cakupan untuk pelanggan aktif
     */
    public function scopeActive($query)
    {
        return $query->where('aktif', true);
    }

    /**
     * Berhenti berlangganan
     */
    public function unsubscribe(): void
    {
        $this->update([
            'aktif' => false,
            'berhenti_langganan_pada' => Carbon::now(),
        ]);
    }

    /**
     * Berlangganan kembali
     */
    public function resubscribe(): void
    {
        $this->update([
            'aktif' => true,
            'berhenti_langganan_pada' => null,
            'berlangganan_pada' => Carbon::now(),
            'token_berhenti' => Str::random(64),
        ]);
    }
}
