<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ShippingController extends Controller
{
    /**
     * Available shipping providers and their rates
     */
    protected $providers = [
        'jne' => [
            'name' => 'JNE',
            'services' => [
                'reg' => ['name' => 'JNE Regular', 'estimated_days' => '2-3', 'base_rate' => 9000, 'per_kg' => 9000],
                'oke' => ['name' => 'JNE OKE', 'estimated_days' => '3-5', 'base_rate' => 7000, 'per_kg' => 7000],
                'yes' => ['name' => 'JNE YES', 'estimated_days' => '1', 'base_rate' => 20000, 'per_kg' => 20000],
            ],
        ],
        'sicepat' => [
            'name' => 'SiCepat',
            'services' => [
                'reg' => ['name' => 'SiCepat Regular', 'estimated_days' => '2-3', 'base_rate' => 8500, 'per_kg' => 8500],
                'best' => ['name' => 'SiCepat BEST', 'estimated_days' => '1-2', 'base_rate' => 15000, 'per_kg' => 15000],
            ],
        ],
        'jnt' => [
            'name' => 'J&T Express',
            'services' => [
                'ez' => ['name' => 'J&T EZ', 'estimated_days' => '2-3', 'base_rate' => 9000, 'per_kg' => 9000],
            ],
        ],
        'anteraja' => [
            'name' => 'AnterAja',
            'services' => [
                'reg' => ['name' => 'AnterAja Regular', 'estimated_days' => '2-3', 'base_rate' => 8000, 'per_kg' => 8000],
                'sameday' => ['name' => 'AnterAja Same Day', 'estimated_days' => '0', 'base_rate' => 25000, 'per_kg' => 25000],
            ],
        ],
    ];

    /**
     * Zone-based multipliers (simplified)
     */
    protected $zoneMultipliers = [
        'jakarta' => 1.0,
        'jabodetabek' => 1.1,
        'jawa' => 1.3,
        'sumatera' => 1.8,
        'kalimantan' => 2.0,
        'sulawesi' => 2.2,
        'bali_nusatenggara' => 2.0,
        'maluku_papua' => 2.5,
    ];

    /**
     * Get available shipping methods
     */
    public function getMethods(): JsonResponse
    {
        $methods = [];

        foreach ($this->providers as $code => $provider) {
            foreach ($provider['services'] as $serviceCode => $service) {
                $methods[] = [
                    'id' => $code . '_' . $serviceCode,
                    'provider' => $provider['name'],
                    'provider_code' => $code,
                    'service' => $service['name'],
                    'service_code' => $serviceCode,
                    'estimated_days' => $service['estimated_days'],
                ];
            }
        }

        return $this->success($methods);
    }

    /**
     * Calculate shipping cost
     */
    public function calculate(Request $request): JsonResponse
    {
        $this->validate($request, [
            'origin_city' => 'nullable|string',
            'destination_city' => 'required|string',
            'destination_province' => 'required|string',
            'weight' => 'required|numeric|min:1', // in grams
            'provider' => 'nullable|string',
        ]);

        $weight = $request->input('weight');
        $weightKg = ceil($weight / 1000); // Round up to nearest kg
        $destination = strtolower($request->input('destination_province'));
        $specificProvider = $request->input('provider');

        // Determine zone
        $zone = $this->determineZone($destination);
        $multiplier = $this->zoneMultipliers[$zone] ?? 1.5;

        $results = [];

        foreach ($this->providers as $providerCode => $provider) {
            if ($specificProvider && $providerCode !== $specificProvider) {
                continue;
            }

            foreach ($provider['services'] as $serviceCode => $service) {
                $baseCost = $service['base_rate'];
                $perKgCost = $service['per_kg'];

                // Calculate total cost
                $cost = ($baseCost + ($perKgCost * max(0, $weightKg - 1))) * $multiplier;
                $cost = ceil($cost / 500) * 500; // Round to nearest 500

                $results[] = [
                    'id' => $providerCode . '_' . $serviceCode,
                    'provider' => $provider['name'],
                    'provider_code' => $providerCode,
                    'service' => $service['name'],
                    'service_code' => $serviceCode,
                    'estimated_days' => $service['estimated_days'],
                    'cost' => (int) $cost,
                    'weight_kg' => $weightKg,
                    'zone' => $zone,
                ];
            }
        }

        // Sort by cost
        usort($results, fn($a, $b) => $a['cost'] <=> $b['cost']);

        return $this->success([
            'shipping_options' => $results,
            'weight_grams' => $weight,
            'weight_kg' => $weightKg,
            'destination' => [
                'city' => $request->input('destination_city'),
                'province' => $request->input('destination_province'),
                'zone' => $zone,
            ],
        ]);
    }

    /**
     * Determine shipping zone based on province
     */
    protected function determineZone(string $province): string
    {
        $province = strtolower($province);

        // Jakarta
        if (str_contains($province, 'jakarta')) {
            return 'jakarta';
        }

        // Jabodetabek
        $jabodetabek = ['bogor', 'depok', 'tangerang', 'bekasi'];
        foreach ($jabodetabek as $city) {
            if (str_contains($province, $city)) {
                return 'jabodetabek';
            }
        }

        // Jawa
        $jawa = ['jawa', 'banten', 'yogyakarta'];
        foreach ($jawa as $keyword) {
            if (str_contains($province, $keyword)) {
                return 'jawa';
            }
        }

        // Sumatera
        if (
            str_contains($province, 'sumatera') || str_contains($province, 'sumatra') ||
            str_contains($province, 'riau') || str_contains($province, 'jambi') ||
            str_contains($province, 'lampung') || str_contains($province, 'aceh') ||
            str_contains($province, 'bangka') || str_contains($province, 'bengkulu')
        ) {
            return 'sumatera';
        }

        // Kalimantan
        if (str_contains($province, 'kalimantan')) {
            return 'kalimantan';
        }

        // Sulawesi
        if (str_contains($province, 'sulawesi') || str_contains($province, 'gorontalo')) {
            return 'sulawesi';
        }

        // Bali & Nusatenggara
        if (
            str_contains($province, 'bali') || str_contains($province, 'nusa tenggara') ||
            str_contains($province, 'nusatenggara') || str_contains($province, 'ntb') ||
            str_contains($province, 'ntt')
        ) {
            return 'bali_nusatenggara';
        }

        // Maluku & Papua
        if (str_contains($province, 'maluku') || str_contains($province, 'papua')) {
            return 'maluku_papua';
        }

        // Default
        return 'jawa';
    }

    /**
     * Get provinces list
     */
    public function getProvinces(): JsonResponse
    {
        $provinces = [
            'Aceh',
            'Sumatera Utara',
            'Sumatera Barat',
            'Riau',
            'Jambi',
            'Sumatera Selatan',
            'Bengkulu',
            'Lampung',
            'Kepulauan Bangka Belitung',
            'Kepulauan Riau',
            'DKI Jakarta',
            'Jawa Barat',
            'Jawa Tengah',
            'DI Yogyakarta',
            'Jawa Timur',
            'Banten',
            'Bali',
            'Nusa Tenggara Barat',
            'Nusa Tenggara Timur',
            'Kalimantan Barat',
            'Kalimantan Tengah',
            'Kalimantan Selatan',
            'Kalimantan Timur',
            'Kalimantan Utara',
            'Sulawesi Utara',
            'Sulawesi Tengah',
            'Sulawesi Selatan',
            'Sulawesi Tenggara',
            'Gorontalo',
            'Sulawesi Barat',
            'Maluku',
            'Maluku Utara',
            'Papua Barat',
            'Papua',
        ];

        return $this->success($provinces);
    }
}
