<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    /**
     * Get all products
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filter by category
        if ($request->has('kategori_id')) {
            $query->where('kategori_id', $request->input('kategori_id'));
        }

        // Filter by active status
        if ($request->has('active')) {
            $query->where('aktif', $request->boolean('active'));
        }

        // Filter by best seller
        if ($request->has('best_seller')) {
            $query->where('terlaris', $request->boolean('best_seller'));
        }

        // Filter by promo
        if ($request->has('promo')) {
            $query->where('promo', $request->boolean('promo'));
        }

        // Filter by retail product
        if ($request->has('retail')) {
            $query->where('produk_retail', $request->boolean('retail'));
        }

        // Search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('deskripsi_singkat', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        // Pagination
        if ($request->has('per_page')) {
            $products = $query->paginate($request->input('per_page', 15));
            return $this->paginatedResponse($products);
        }

        return $this->successResponse($query->get());
    }

    /**
     * Get single product
     */
    public function show($id)
    {
        $product = Product::with(['category', 'designTemplates'])->find($id);

        if (!$product) {
            return $this->errorResponse('Produk tidak ditemukan', 404);
        }

        return $this->successResponse($product);
    }

    /**
     * Get product by slug
     */
    public function showBySlug($slug)
    {
        $product = Product::with(['category', 'designTemplates'])->where('slug', $slug)->first();

        if (!$product) {
            return $this->errorResponse('Produk tidak ditemukan', 404);
        }

        return $this->successResponse($product);
    }

    /**
     * Create new product
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'kategori_id' => 'required|exists:categories,id',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'deskripsi_singkat' => 'nullable|string|max:500',
            'gambar' => 'nullable|array',
            'harga_dasar' => 'required|numeric|min:0',
            'ukuran' => 'nullable|array',
            'bahan' => 'nullable|array',
            'sisi_cetak' => 'nullable|array',
            'finishing' => 'nullable|array',
            'tier_jumlah' => 'nullable|array',
            'min_pesan' => 'nullable|integer|min:1',
            'estimasi_hari' => 'nullable|integer|min:1',
            'berat_per_pcs' => 'nullable|integer|min:0',
            'tipe_file_diperbolehkan' => 'nullable|array',
            'ukuran_file_maks' => 'nullable|integer|min:1',
        ]);

        $slug = Str::slug($request->input('nama'));
        if (Product::where('slug', $slug)->exists()) {
            throw ValidationException::withMessages([
                'nama' => 'Produk dengan nama tersebut sudah ada',
            ]);
        }

        $data = $request->all();
        $data['slug'] = $slug;
        $data['aktif'] = true;

        $product = Product::create($data);

        return $this->successResponse($product->load('category'), 'Produk berhasil dibuat', 201);
    }

    /**
     * Update product
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->errorResponse('Produk tidak ditemukan', 404);
        }

        $this->validate($request, [
            'kategori_id' => 'sometimes|exists:categories,id',
            'nama' => 'sometimes|required|string|max:255',
            'deskripsi' => 'nullable|string',
            'deskripsi_singkat' => 'nullable|string|max:500',
            'gambar' => 'nullable|array',
            'harga_dasar' => 'sometimes|numeric|min:0',
            'ukuran' => 'nullable|array',
            'bahan' => 'nullable|array',
            'sisi_cetak' => 'nullable|array',
            'finishing' => 'nullable|array',
            'tier_jumlah' => 'nullable|array',
            'terlaris' => 'nullable|boolean',
            'promo' => 'nullable|boolean',
            'persen_promo' => 'nullable|integer|min:0|max:100',
            'min_pesan' => 'nullable|integer|min:1',
            'estimasi_hari' => 'nullable|integer|min:1',
            'produk_retail' => 'nullable|boolean',
            'butuh_file_desain' => 'nullable|boolean',
            'aktif' => 'nullable|boolean',
        ]);

        $data = $request->all();

        if ($request->has('nama')) {
            $slug = Str::slug($request->input('nama'));
            if (Product::where('slug', $slug)->where('id', '!=', $id)->exists()) {
                throw ValidationException::withMessages([
                    'nama' => 'Produk dengan nama tersebut sudah ada',
                ]);
            }
            $data['slug'] = $slug;
        }

        $product->update($data);

        return $this->successResponse($product->load('category'), 'Produk berhasil diupdate');
    }

    /**
     * Delete product
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->errorResponse('Produk tidak ditemukan', 404);
        }

        $product->delete();

        return $this->successResponse(null, 'Produk berhasil dihapus');
    }

    /**
     * Get products by category slug
     */
    public function byCategory($categorySlug)
    {
        $products = Product::with('category')
            ->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            })
            ->where('aktif', true)
            ->get();

        return $this->successResponse($products);
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return $this->successResponse([
                'products' => [],
                'categories' => [],
                'total' => 0
            ]);
        }

        $products = Product::with('category')
            ->where('aktif', true)
            ->where(function ($q) use ($query) {
                $q->where('nama', 'like', "%{$query}%")
                    ->orWhere('deskripsi_singkat', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get();

        return $this->successResponse([
            'products' => $products,
            'total' => $products->count()
        ]);
    }

    /**
     * Get popular products with sales statistics
     */
    public function popularProducts(Request $request)
    {
        $limit = $request->input('limit', 5);

        // Get product sales count from order_items
        $productSales = OrderItem::select('produk_id', DB::raw('COUNT(*) as order_count'), DB::raw('SUM(jumlah) as total_sold'))
            ->groupBy('produk_id')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get()
            ->keyBy('produk_id');

        if ($productSales->isEmpty()) {
            // Fallback: get best seller products if no orders yet
            $products = Product::with('category')
                ->where('aktif', true)
                ->where('terlaris', true)
                ->limit($limit)
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'nama' => $product->nama,
                        'slug' => $product->slug,
                        'gambar' => $product->gambar[0] ?? null,
                        'harga_dasar' => $product->harga_dasar,
                        'kategori' => $product->category ? $product->category->nama : null,
                        'sales_count' => 0,
                        'total_sold' => 0,
                    ];
                });

            return $this->successResponse($products);
        }

        // Get products with sales data
        $productIds = $productSales->keys()->toArray();
        $products = Product::with('category')
            ->whereIn('id', $productIds)
            ->get()
            ->map(function ($product) use ($productSales) {
                $sales = $productSales->get($product->id);
                return [
                    'id' => $product->id,
                    'nama' => $product->nama,
                    'slug' => $product->slug,
                    'gambar' => $product->gambar[0] ?? null,
                    'harga_dasar' => $product->harga_dasar,
                    'kategori' => $product->category ? $product->category->nama : null,
                    'sales_count' => $sales ? $sales->order_count : 0,
                    'total_sold' => $sales ? $sales->total_sold : 0,
                ];
            })
            ->sortByDesc('total_sold')
            ->values();

        return $this->successResponse($products);
    }
}
