<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Get all categories
     */
    public function index(Request $request)
    {
        $query = Category::query();

        // Filter by active status
        if ($request->has('active')) {
            $query->where('aktif', $request->boolean('active'));
        }

        // Search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->input('sort_by', 'urutan');
        $sortDir = $request->input('sort_dir', 'asc');
        $query->orderBy($sortBy, $sortDir);

        // Pagination or all
        if ($request->has('per_page')) {
            $categories = $query->paginate($request->input('per_page', 15));
            return $this->paginatedResponse($categories);
        }

        return $this->successResponse($query->get());
    }

    /**
     * Get single category
     */
    public function show($id)
    {
        $category = Category::with('products')->find($id);

        if (!$category) {
            return $this->errorResponse('Kategori tidak ditemukan', 404);
        }

        return $this->successResponse($category);
    }

    /**
     * Get category by slug
     */
    public function showBySlug($slug)
    {
        $category = Category::with('products')->where('slug', $slug)->first();

        if (!$category) {
            return $this->errorResponse('Kategori tidak ditemukan', 404);
        }

        return $this->successResponse($category);
    }

    /**
     * Create new category
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'ikon' => 'nullable|string',
            'gambar' => 'nullable|string',
            'urutan' => 'nullable|integer',
        ]);

        $category = Category::create([
            'nama' => $request->input('nama'),
            'slug' => Str::slug($request->input('nama')),
            'deskripsi' => $request->input('deskripsi'),
            'ikon' => $request->input('ikon'),
            'gambar' => $request->input('gambar'),
            'urutan' => $request->input('urutan', 0),
            'aktif' => true,
        ]);

        return $this->successResponse($category, 'Kategori berhasil dibuat', 201);
    }

    /**
     * Update category
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->errorResponse('Kategori tidak ditemukan', 404);
        }

        $this->validate($request, [
            'nama' => 'sometimes|required|string|max:255',
            'deskripsi' => 'nullable|string',
            'ikon' => 'nullable|string',
            'gambar' => 'nullable|string',
            'urutan' => 'nullable|integer',
            'aktif' => 'nullable|boolean',
        ]);

        $data = $request->only(['nama', 'deskripsi', 'ikon', 'gambar', 'urutan', 'aktif']);

        if ($request->has('nama')) {
            $data['slug'] = Str::slug($request->input('nama'));
        }

        $category->update($data);

        return $this->successResponse($category, 'Kategori berhasil diupdate');
    }

    /**
     * Delete category
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->errorResponse('Kategori tidak ditemukan', 404);
        }

        // Check if category has products
        if ($category->products()->count() > 0) {
            return $this->errorResponse('Tidak dapat menghapus kategori yang memiliki produk', 400);
        }

        $category->delete();

        return $this->successResponse(null, 'Kategori berhasil dihapus');
    }
}
