<?php

namespace App\Services;

/**
 * Single source of truth for shipping providers/rates, shared by
 * ShippingController (quotes shown to the customer) and OrderController
 * (re-validates the quote server-side before accepting an order).
 */
class ShippingCalculator
{
    protected static array $providers = [
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

    protected static array $zoneMultipliers = [
        'jakarta' => 1.0,
        'jabodetabek' => 1.1,
        'jawa' => 1.3,
        'sumatera' => 1.8,
        'kalimantan' => 2.0,
        'sulawesi' => 2.2,
        'bali_nusatenggara' => 2.0,
        'maluku_papua' => 2.5,
    ];

    public static function providers(): array
    {
        return self::$providers;
    }

    public static function determineZone(string $province): string
    {
        $province = strtolower($province);

        if (str_contains($province, 'jakarta')) {
            return 'jakarta';
        }

        $jabodetabek = ['bogor', 'depok', 'tangerang', 'bekasi'];
        foreach ($jabodetabek as $city) {
            if (str_contains($province, $city)) {
                return 'jabodetabek';
            }
        }

        $jawa = ['jawa', 'banten', 'yogyakarta'];
        foreach ($jawa as $keyword) {
            if (str_contains($province, $keyword)) {
                return 'jawa';
            }
        }

        if (
            str_contains($province, 'sumatera') || str_contains($province, 'sumatra') ||
            str_contains($province, 'riau') || str_contains($province, 'jambi') ||
            str_contains($province, 'lampung') || str_contains($province, 'aceh') ||
            str_contains($province, 'bangka') || str_contains($province, 'bengkulu')
        ) {
            return 'sumatera';
        }

        if (str_contains($province, 'kalimantan')) {
            return 'kalimantan';
        }

        if (str_contains($province, 'sulawesi') || str_contains($province, 'gorontalo')) {
            return 'sulawesi';
        }

        if (
            str_contains($province, 'bali') || str_contains($province, 'nusa tenggara') ||
            str_contains($province, 'nusatenggara') || str_contains($province, 'ntb') ||
            str_contains($province, 'ntt')
        ) {
            return 'bali_nusatenggara';
        }

        if (str_contains($province, 'maluku') || str_contains($province, 'papua')) {
            return 'maluku_papua';
        }

        return 'jawa';
    }

    /**
     * Quote a single provider+service combo for a given weight/destination.
     * Returns null if the combo doesn't exist (invalid/tampered id).
     */
    public static function calculate(?string $providerCode, ?string $serviceCode, float $weightKg, string $province): ?array
    {
        $provider = self::$providers[$providerCode] ?? null;
        $service = $provider['services'][$serviceCode] ?? null;

        if (!$provider || !$service) {
            return null;
        }

        $zone = self::determineZone($province);
        $multiplier = self::$zoneMultipliers[$zone] ?? 1.5;

        $cost = ($service['base_rate'] + ($service['per_kg'] * max(0, $weightKg - 1))) * $multiplier;
        $cost = ceil($cost / 500) * 500;

        return [
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

    /**
     * Quote every provider+service combo (optionally filtered to one provider)
     * for a given weight/destination, cheapest first.
     */
    public static function calculateAll(float $weightKg, string $province, ?string $onlyProvider = null): array
    {
        $results = [];

        foreach (self::$providers as $providerCode => $provider) {
            if ($onlyProvider && $providerCode !== $onlyProvider) {
                continue;
            }

            foreach ($provider['services'] as $serviceCode => $service) {
                $results[] = self::calculate($providerCode, $serviceCode, $weightKg, $province);
            }
        }

        usort($results, fn ($a, $b) => $a['cost'] <=> $b['cost']);

        return $results;
    }
}
