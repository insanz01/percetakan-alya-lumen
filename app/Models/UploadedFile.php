<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UploadedFile extends Model
{
    use HasUuids;

    protected $fillable = [
        'pengguna_id',
        'nama_asli',
        'nama_disimpan',
        'jalur',
        'penyimpanan',
        'jenis_mime',
        'ukuran',
        'tipe',
        'terkait_id',
        'terkait_tipe',
    ];

    protected $casts = [
        'ukuran' => 'integer',
    ];

    /**
     * Ambil pengguna yang mengunggah file
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengguna_id');
    }

    /**
     * Ambil model terkait (polimorfik)
     */
    public function related()
    {
        return $this->morphTo();
    }

    /**
     * Ambil URL file
     */
    public function getUrlAttribute(): string
    {
        if ($this->penyimpanan === 'public') {
            return url('storage/' . $this->jalur);
        }

        return url('api/v1/files/' . $this->id);
    }

    /**
     * Ambil ukuran file dalam format yang dapat dibaca
     */
    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->ukuran;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Periksa apakah file adalah gambar
     */
    public function isImage(): bool
    {
        return str_starts_with($this->jenis_mime, 'image/');
    }

    /**
     * Periksa apakah file adalah PDF
     */
    public function isPdf(): bool
    {
        return $this->jenis_mime === 'application/pdf';
    }

    /**
     * Cakupan berdasarkan tipe
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('tipe', $type);
    }

    /**
     * Cakupan berdasarkan terkait
     */
    public function scopeForRelated($query, string $relatedType, string $relatedId)
    {
        return $query->where('terkait_tipe', $relatedType)
            ->where('terkait_id', $relatedId);
    }
}
