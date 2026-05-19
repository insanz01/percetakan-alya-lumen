<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'kunci',
        'nilai',
        'tipe',
        'grup',
    ];

    /**
     * Ambil nilai pengaturan berdasarkan kunci
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = self::where('kunci', $key)->first();

        if (!$setting) {
            return $default;
        }

        // Konversi nilai berdasarkan tipe
        return match ($setting->tipe) {
            'boolean' => filter_var($setting->nilai, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($setting->nilai, true),
            'integer' => (int) $setting->nilai,
            default => $setting->nilai,
        };
    }

    /**
     * Simpan nilai pengaturan
     */
    public static function setValue(string $key, $value, string $type = 'string', string $group = 'general'): self
    {
        // Konversi nilai ke string untuk penyimpanan
        $storedValue = match ($type) {
            'boolean' => $value ? 'true' : 'false',
            'json' => json_encode($value),
            default => (string) $value,
        };

        return self::updateOrCreate(
            ['kunci' => $key],
            ['nilai' => $storedValue, 'tipe' => $type, 'grup' => $group]
        );
    }

    /**
     * Ambil semua pengaturan berdasarkan grup
     */
    public static function getByGroup(string $group): array
    {
        $settings = self::where('grup', $group)->get();

        $result = [];
        foreach ($settings as $setting) {
            $result[$setting->kunci] = self::getValue($setting->kunci);
        }

        return $result;
    }

    /**
     * Ambil semua pengaturan sebagai pasangan kunci-nilai
     */
    public static function getAllAsArray(): array
    {
        $settings = self::all();

        $result = [];
        foreach ($settings as $setting) {
            $result[$setting->kunci] = self::getValue($setting->kunci);
        }

        return $result;
    }
}
