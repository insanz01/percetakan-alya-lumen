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

        $settings = Setting::whereIn('key', $publicKeys)->get();

        $result = [];
        foreach ($settings as $setting) {
            $result[$setting->key] = Setting::getValue($setting->key);
        }

        return $this->success($result);
    }

    /**
     * Get all settings (Admin)
     */
    public function index(): JsonResponse
    {
        $settings = Setting::all()->groupBy('group');

        $result = [];
        foreach ($settings as $group => $groupSettings) {
            $result[$group] = [];
            foreach ($groupSettings as $setting) {
                $result[$group][$setting->key] = [
                    'value' => Setting::getValue($setting->key),
                    'type' => $setting->type,
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
        $setting = Setting::where('key', $key)->first();

        if (!$setting) {
            return $this->error('Setting not found', 404);
        }

        return $this->success([
            'key' => $setting->key,
            'value' => Setting::getValue($key),
            'type' => $setting->type,
            'group' => $setting->group,
        ]);
    }

    /**
     * Update settings (bulk)
     */
    public function update(Request $request): JsonResponse
    {
        $this->validate($request, [
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'present',
        ]);

        $updated = [];

        foreach ($request->input('settings') as $item) {
            $setting = Setting::where('key', $item['key'])->first();

            if ($setting) {
                Setting::setValue(
                    $item['key'],
                    $item['value'],
                    $setting->type,
                    $setting->group
                );
                $updated[] = $item['key'];
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
            'value' => 'present',
        ]);

        $setting = Setting::where('key', $key)->first();

        if (!$setting) {
            return $this->error('Setting not found', 404);
        }

        Setting::setValue($key, $request->input('value'), $setting->type, $setting->group);

        return $this->success([
            'key' => $key,
            'value' => Setting::getValue($key),
        ], 'Setting updated successfully');
    }
}
