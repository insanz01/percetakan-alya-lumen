<?php

namespace App\Http\Controllers;

use App\Services\ShippingCalculator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ShippingController extends Controller
{
    /**
     * Get available shipping methods
     */
    public function getMethods(): JsonResponse
    {
        $methods = [];

        foreach (ShippingCalculator::providers() as $code => $provider) {
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
        $destination = $request->input('destination_province');
        $specificProvider = $request->input('provider');

        $results = ShippingCalculator::calculateAll($weightKg, $destination, $specificProvider);

        return $this->success([
            'shipping_options' => $results,
            'weight_grams' => $weight,
            'weight_kg' => $weightKg,
            'destination' => [
                'city' => $request->input('destination_city'),
                'province' => $request->input('destination_province'),
                'zone' => ShippingCalculator::determineZone($destination),
            ],
        ]);
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
