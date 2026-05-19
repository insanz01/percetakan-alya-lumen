<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ContactMessage extends Model
{
    use HasUuids;

    protected $fillable = [
        'nama',
        'email',
        'telepon',
        'subjek',
        'pesan',
        'status',
        'catatan_admin',
        'dibalas_pada',
    ];

    protected $casts = [
        'dibalas_pada' => 'datetime',
    ];

    /**
     * Cakupan untuk pesan baru
     */
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Cakupan untuk pesan yang belum dibaca
     */
    public function scopeUnread($query)
    {
        return $query->whereIn('status', ['new', 'read']);
    }

    /**
     * Tandai sebagai telah dibaca
     */
    public function markAsRead(): void
    {
        if ($this->status === 'new') {
            $this->update(['status' => 'read']);
        }
    }

    /**
     * Tandai sebagai telah dibalas
     */
    public function markAsReplied(): void
    {
        $this->update([
            'status' => 'replied',
            'dibalas_pada' => Carbon::now(),
        ]);
    }
}
