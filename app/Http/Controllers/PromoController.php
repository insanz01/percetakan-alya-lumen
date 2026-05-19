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
            $query->where('aktif', $request->boolean('active'));
        }

        // Filter by valid (currently applicable)
        if ($request->boolean('valid_only')) {
            $now = Carbon::now();
            $query->where('aktif', true)
                ->where(function ($q) use ($now) {
                    $q->whereNull('tanggal_mulai')
                        ->orWhere('tanggal_mulai', '<=', $now);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('tanggal_berakhir')
                        ->orWhere('tanggal_berakhir', '>=', $now);
                })
                ->where(function ($q) {
                    $q->whereNull('batas_penggunaan')
                        ->orWhereRaw('jumlah_penggunaan < batas_penggunaan');
                });
        }

        // Search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('kode', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
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
            'kode' => 'required|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $promo = Promo::where('kode', strtoupper($request->input('kode')))->first();

        if (!$promo) {
            return $this->errorResponse('Kode promo tidak ditemukan', 404);
        }

        if (!$promo->isValid()) {
            return $this->errorResponse('Kode promo tidak valid atau sudah kadaluarsa', 400);
        }

        $amount = $request->input('amount');

        if ($amount < $promo->min_beli) {
            return $this->errorResponse("Minimal pembelian Rp " . number_format($promo->min_beli, 0, ',', '.'), 400);
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
            'kode' => 'required|string|unique:promos,kode',
            'deskripsi' => 'nullable|string',
            'tipe' => 'required|in:percentage,fixed',
            'diskon' => 'required|numeric|min:0',
            'min_beli' => 'nullable|numeric|min:0',
            'maks_diskon' => 'nullable|numeric|min:0',
            'batas_penggunaan' => 'nullable|integer|min:1',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date|after:tanggal_mulai',
        ]);

        $promo = Promo::create([
            'kode' => strtoupper($request->input('kode')),
            'deskripsi' => $request->input('deskripsi'),
            'tipe' => $request->input('tipe'),
            'diskon' => $request->input('diskon'),
            'min_beli' => $request->input('min_beli', 0),
            'maks_diskon' => $request->input('maks_diskon'),
            'batas_penggunaan' => $request->input('batas_penggunaan'),
            'jumlah_penggunaan' => 0,
            'tanggal_mulai' => $request->input('tanggal_mulai'),
            'tanggal_berakhir' => $request->input('tanggal_berakhir'),
            'aktif' => true,
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
            'kode' => 'sometimes|required|string|unique:promos,kode,' . $id,
            'deskripsi' => 'nullable|string',
            'tipe' => 'sometimes|in:percentage,fixed',
            'diskon' => 'sometimes|numeric|min:0',
            'min_beli' => 'nullable|numeric|min:0',
            'maks_diskon' => 'nullable|numeric|min:0',
            'batas_penggunaan' => 'nullable|integer|min:1',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date',
            'aktif' => 'nullable|boolean',
        ]);

        $data = $request->all();

        if ($request->has('kode')) {
            $data['kode'] = strtoupper($request->input('kode'));
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

        $promo->increment('jumlah_penggunaan');

        return $this->successResponse($promo, 'Usage count berhasil diupdate');
    }
}
