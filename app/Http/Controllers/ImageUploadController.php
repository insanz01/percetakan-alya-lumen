<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadController extends Controller
{
    /**
     * Allowed image MIME types
     */
    protected $allowedTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    /**
     * Maximum file size (5MB)
     */
    protected $maxSize = 5 * 1024 * 1024;

    /**
     * Upload product image
     */
    public function uploadProductImage(Request $request, string $productId): JsonResponse
    {
        $product = Product::find($productId);

        if (!$product) {
            return $this->error('Product not found', 404);
        }

        $this->validate($request, [
            'image' => 'required|file',
            'replace_index' => 'nullable|integer|min:0',
        ]);

        $file = $request->file('image');

        // Validate file type
        if (!in_array($file->getMimeType(), $this->allowedTypes)) {
            return $this->error('Tipe file tidak diizinkan. Gunakan: JPEG, PNG, GIF, atau WebP', 422);
        }

        // Validate file size
        if ($file->getSize() > $this->maxSize) {
            return $this->error('Ukuran file maksimal 5 MB', 422);
        }

        // Generate unique filename
        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = 'product-' . $productId . '-' . Str::random(8) . '.' . $extension;

        // Store file in public disk
        $path = 'products/' . date('Y/m');
        $fullPath = $file->storeAs($path, $filename, 'public');

        // Generate URL
        $imageUrl = url('storage/' . $fullPath);

        // Update product images array
        $images = $product->images ?? [];
        $replaceIndex = $request->input('replace_index');

        if ($replaceIndex !== null && isset($images[$replaceIndex])) {
            // Replace existing image
            $oldImage = $images[$replaceIndex];
            $this->deleteOldImage($oldImage);
            $images[$replaceIndex] = $imageUrl;
        } else {
            // Add new image
            $images[] = $imageUrl;
        }

        $product->images = $images;
        $product->save();

        return $this->success([
            'url' => $imageUrl,
            'images' => $images,
            'index' => $replaceIndex ?? (count($images) - 1),
        ], 'Gambar produk berhasil diupload');
    }

    /**
     * Delete product image
     */
    public function deleteProductImage(Request $request, string $productId): JsonResponse
    {
        $product = Product::find($productId);

        if (!$product) {
            return $this->error('Product not found', 404);
        }

        $this->validate($request, [
            'index' => 'required|integer|min:0',
        ]);

        $images = $product->images ?? [];
        $index = $request->input('index');

        if (!isset($images[$index])) {
            return $this->error('Image index not found', 404);
        }

        // Delete file from storage
        $this->deleteOldImage($images[$index]);

        // Remove from array
        array_splice($images, $index, 1);

        $product->images = array_values($images);
        $product->save();

        return $this->success([
            'images' => $product->images,
        ], 'Gambar produk berhasil dihapus');
    }

    /**
     * Upload category image
     */
    public function uploadCategoryImage(Request $request, string $categoryId): JsonResponse
    {
        $category = Category::find($categoryId);

        if (!$category) {
            return $this->error('Category not found', 404);
        }

        $this->validate($request, [
            'image' => 'required|file',
        ]);

        $file = $request->file('image');

        // Validate file type
        if (!in_array($file->getMimeType(), $this->allowedTypes)) {
            return $this->error('Tipe file tidak diizinkan. Gunakan: JPEG, PNG, GIF, atau WebP', 422);
        }

        // Validate file size
        if ($file->getSize() > $this->maxSize) {
            return $this->error('Ukuran file maksimal 5 MB', 422);
        }

        // Delete old image if exists and is local
        if ($category->image) {
            $this->deleteOldImage($category->image);
        }

        // Generate unique filename
        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = 'category-' . $categoryId . '-' . Str::random(8) . '.' . $extension;

        // Store file in public disk
        $path = 'categories';
        $fullPath = $file->storeAs($path, $filename, 'public');

        // Generate URL
        $imageUrl = url('storage/' . $fullPath);

        // Update category
        $category->image = $imageUrl;
        $category->save();

        return $this->success([
            'url' => $imageUrl,
        ], 'Gambar kategori berhasil diupload');
    }

    /**
     * Delete category image
     */
    public function deleteCategoryImage(string $categoryId): JsonResponse
    {
        $category = Category::find($categoryId);

        if (!$category) {
            return $this->error('Category not found', 404);
        }

        if ($category->image) {
            $this->deleteOldImage($category->image);
            $category->image = null;
            $category->save();
        }

        return $this->success(null, 'Gambar kategori berhasil dihapus');
    }

    /**
     * Upload generic image (for editor, content, etc.)
     */
    public function uploadImage(Request $request): JsonResponse
    {
        $this->validate($request, [
            'image' => 'required|file',
            'folder' => 'nullable|string|max:50',
        ]);

        $file = $request->file('image');

        // Validate file type
        if (!in_array($file->getMimeType(), $this->allowedTypes)) {
            return $this->error('Tipe file tidak diizinkan. Gunakan: JPEG, PNG, GIF, atau WebP', 422);
        }

        // Validate file size
        if ($file->getSize() > $this->maxSize) {
            return $this->error('Ukuran file maksimal 5 MB', 422);
        }

        // Generate unique filename
        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = Str::uuid() . '.' . $extension;

        // Determine folder
        $folder = $request->input('folder', 'uploads');
        $folder = preg_replace('/[^a-zA-Z0-9_-]/', '', $folder); // Sanitize

        // Store file in public disk
        $path = $folder . '/' . date('Y/m');
        $fullPath = $file->storeAs($path, $filename, 'public');

        // Generate URL
        $imageUrl = url('storage/' . $fullPath);

        return $this->success([
            'url' => $imageUrl,
            'path' => $fullPath,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ], 'Gambar berhasil diupload');
    }

    /**
     * Delete old image from storage if it's a local file
     */
    protected function deleteOldImage(string $imageUrl): void
    {
        // Only delete if it's a local storage file
        if (strpos($imageUrl, '/storage/') !== false) {
            $path = str_replace(url('storage/'), '', $imageUrl);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }
}
