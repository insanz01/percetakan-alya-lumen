<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Get all products
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // Filter by active status
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        // Filter by best seller
        if ($request->has('best_seller')) {
            $query->where('is_best_seller', $request->boolean('best_seller'));
        }

        // Filter by promo
        if ($request->has('promo')) {
            $query->where('is_promo', $request->boolean('promo'));
        }

        // Filter by retail product
        if ($request->has('retail')) {
            $query->where('is_retail_product', $request->boolean('retail'));
        }

        // Search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
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
        $product = Product::with('category')->find($id);

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
        $product = Product::with('category')->where('slug', $slug)->first();

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
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'images' => 'nullable|array',
            'base_price' => 'required|numeric|min:0',
            'sizes' => 'nullable|array',
            'materials' => 'nullable|array',
            'print_sides' => 'nullable|array',
            'finishings' => 'nullable|array',
            'quantity_tiers' => 'nullable|array',
            'min_order_qty' => 'nullable|integer|min:1',
            'estimated_days' => 'nullable|integer|min:1',
            'weight_per_piece' => 'nullable|integer|min:0',
            'allowed_file_types' => 'nullable|array',
            'max_file_size' => 'nullable|integer|min:1',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->input('name'));
        $data['is_active'] = true;

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
            'category_id' => 'sometimes|exists:categories,id',
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'images' => 'nullable|array',
            'base_price' => 'sometimes|numeric|min:0',
            'sizes' => 'nullable|array',
            'materials' => 'nullable|array',
            'print_sides' => 'nullable|array',
            'finishings' => 'nullable|array',
            'quantity_tiers' => 'nullable|array',
            'is_best_seller' => 'nullable|boolean',
            'is_promo' => 'nullable|boolean',
            'promo_percentage' => 'nullable|integer|min:0|max:100',
            'min_order_qty' => 'nullable|integer|min:1',
            'estimated_days' => 'nullable|integer|min:1',
            'is_retail_product' => 'nullable|boolean',
            'requires_design_file' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->all();

        if ($request->has('name')) {
            $data['slug'] = Str::slug($request->input('name'));
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
            ->where('is_active', true)
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
            ->where('is_active', true)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('short_description', 'like', "%{$query}%");
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
        $productSales = OrderItem::select('product_id', DB::raw('COUNT(*) as order_count'), DB::raw('SUM(quantity) as total_sold'))
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get()
            ->keyBy('product_id');

        if ($productSales->isEmpty()) {
            // Fallback: get best seller products if no orders yet
            $products = Product::with('category')
                ->where('is_active', true)
                ->where('is_best_seller', true)
                ->limit($limit)
                ->get()
                ->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'image' => $product->images[0] ?? null,
                        'base_price' => $product->base_price,
                        'category' => $product->category ? $product->category->name : null,
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
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'image' => $product->images[0] ?? null,
                    'base_price' => $product->base_price,
                    'category' => $product->category ? $product->category->name : null,
                    'sales_count' => $sales ? $sales->order_count : 0,
                    'total_sold' => $sales ? $sales->total_sold : 0,
                ];
            })
            ->sortByDesc('total_sold')
            ->values();

        return $this->successResponse($products);
    }
}
