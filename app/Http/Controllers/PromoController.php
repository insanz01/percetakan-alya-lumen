<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PromoController extends Controller
{
    /**
     * Get all promos
     */
    public function index(Request $request)
    {
        $query = Promo::query();

        // Filter by active status
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        // Filter by valid (currently applicable)
        if ($request->boolean('valid_only')) {
            $now = Carbon::now();
            $query->where('is_active', true)
                ->where(function ($q) use ($now) {
                    $q->whereNull('start_date')
                        ->orWhere('start_date', '<=', $now);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('end_date')
                        ->orWhere('end_date', '>=', $now);
                })
                ->where(function ($q) {
                    $q->whereNull('usage_limit')
                        ->orWhereRaw('usage_count < usage_limit');
                });
        }

        // Search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        // Pagination
        if ($request->has('per_page')) {
            $promos = $query->paginate($request->input('per_page', 15));
            return $this->paginatedResponse($promos);
        }

        return $this->successResponse($query->get());
    }

    /**
     * Get single promo
     */
    public function show($id)
    {
        $promo = Promo::find($id);

        if (!$promo) {
            return $this->errorResponse('Promo tidak ditemukan', 404);
        }

        return $this->successResponse($promo);
    }

    /**
     * Validate promo code
     */
    public function validateCode(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $promo = Promo::where('code', strtoupper($request->input('code')))->first();

        if (!$promo) {
            return $this->errorResponse('Kode promo tidak ditemukan', 404);
        }

        if (!$promo->isValid()) {
            return $this->errorResponse('Kode promo tidak valid atau sudah kadaluarsa', 400);
        }

        $amount = $request->input('amount');

        if ($amount < $promo->min_purchase) {
            return $this->errorResponse("Minimal pembelian Rp " . number_format($promo->min_purchase, 0, ',', '.'), 400);
        }

        $discount = $promo->calculateDiscount($amount);

        return $this->successResponse([
            'promo' => $promo,
            'discount' => $discount,
            'final_amount' => $amount - $discount,
        ], 'Kode promo valid');
    }

    /**
     * Create new promo
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|string|unique:promos,code',
            'description' => 'nullable|string',
            'type' => 'required|in:percentage,fixed',
            'discount' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $promo = Promo::create([
            'code' => strtoupper($request->input('code')),
            'description' => $request->input('description'),
            'type' => $request->input('type'),
            'discount' => $request->input('discount'),
            'min_purchase' => $request->input('min_purchase', 0),
            'max_discount' => $request->input('max_discount'),
            'usage_limit' => $request->input('usage_limit'),
            'usage_count' => 0,
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'is_active' => true,
        ]);

        return $this->successResponse($promo, 'Promo berhasil dibuat', 201);
    }

    /**
     * Update promo
     */
    public function update(Request $request, $id)
    {
        $promo = Promo::find($id);

        if (!$promo) {
            return $this->errorResponse('Promo tidak ditemukan', 404);
        }

        $this->validate($request, [
            'code' => 'sometimes|required|string|unique:promos,code,' . $id,
            'description' => 'nullable|string',
            'type' => 'sometimes|in:percentage,fixed',
            'discount' => 'sometimes|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->all();

        if ($request->has('code')) {
            $data['code'] = strtoupper($request->input('code'));
        }

        $promo->update($data);

        return $this->successResponse($promo, 'Promo berhasil diupdate');
    }

    /**
     * Delete promo
     */
    public function destroy($id)
    {
        $promo = Promo::find($id);

        if (!$promo) {
            return $this->errorResponse('Promo tidak ditemukan', 404);
        }

        $promo->delete();

        return $this->successResponse(null, 'Promo berhasil dihapus');
    }

    /**
     * Increment promo usage
     */
    public function incrementUsage($id)
    {
        $promo = Promo::find($id);

        if (!$promo) {
            return $this->errorResponse('Promo tidak ditemukan', 404);
        }

        $promo->increment('usage_count');

        return $this->successResponse($promo, 'Usage count berhasil diupdate');
    }
}
