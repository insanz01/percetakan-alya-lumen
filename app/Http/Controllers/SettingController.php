<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    /**
     * Get all settings (Public - limited)
     */
    public function publicSettings(): JsonResponse
    {
        // Only return public-safe settings
        $publicKeys = [
            'store_name',
            'store_tagline',
            'store_description',
            'store_email',
            'store_phone',
            'store_whatsapp',
            'store_address',
        ];

        $settings = Setting::whereIn('kunci', $publicKeys)->get();

        $result = [];
        foreach ($settings as $setting) {
            $result[$setting->kunci] = Setting::getValue($setting->kunci);
        }

        return $this->success($result);
    }

    /**
     * Get all settings (Admin)
     */
    public function index(): JsonResponse
    {
        $settings = Setting::all()->groupBy('grup');

        $result = [];
        foreach ($settings as $group => $groupSettings) {
            $result[$group] = [];
            foreach ($groupSettings as $setting) {
                $result[$group][$setting->kunci] = [
                    'nilai' => Setting::getValue($setting->kunci),
                    'tipe' => $setting->tipe,
                ];
            }
        }

        return $this->success($result);
    }

    /**
     * Get settings by group
     */
    public function byGroup(string $group): JsonResponse
    {
        $settings = Setting::getByGroup($group);
        return $this->success($settings);
    }

    /**
     * Get single setting
     */
    public function show(string $key): JsonResponse
    {
        $setting = Setting::where('kunci', $key)->first();

        if (!$setting) {
            return $this->error('Setting not found', 404);
        }

        return $this->success([
            'kunci' => $setting->kunci,
            'nilai' => Setting::getValue($key),
            'tipe' => $setting->tipe,
            'grup' => $setting->grup,
        ]);
    }

    /**
     * Update settings (bulk)
     */
    public function update(Request $request): JsonResponse
    {
        $this->validate($request, [
            'settings' => 'required|array',
            'settings.*.kunci' => 'required|string',
            'settings.*.nilai' => 'present',
        ]);

        $updated = [];

        foreach ($request->input('settings') as $item) {
            $setting = Setting::where('kunci', $item['kunci'])->first();

            if ($setting) {
                Setting::setValue(
                    $item['kunci'],
                    $item['nilai'],
                    $setting->tipe,
                    $setting->grup
                );
                $updated[] = $item['kunci'];
            }
        }

        return $this->success([
            'updated' => $updated,
            'count' => count($updated),
        ], 'Settings updated successfully');
    }

    /**
     * Update single setting
     */
    public function updateSingle(Request $request, string $key): JsonResponse
    {
        $this->validate($request, [
            'nilai' => 'present',
        ]);

        $setting = Setting::where('kunci', $key)->first();

        if (!$setting) {
            return $this->error('Setting not found', 404);
        }

        Setting::setValue($key, $request->input('nilai'), $setting->tipe, $setting->grup);

        return $this->success([
            'kunci' => $key,
            'nilai' => Setting::getValue($key),
        ], 'Setting updated successfully');
    }
}
