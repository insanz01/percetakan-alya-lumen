<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductDesignTemplate;
use Illuminate\Http\Request;

class ProductDesignTemplateController extends Controller
{
    /**
     * Add a design template option to a product. The image itself is
     * uploaded beforehand via the generic /admin/images/upload endpoint;
     * this just records the (name, url) pair against the product.
     */
    public function store(Request $request, string $productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            return $this->errorResponse('Produk tidak ditemukan', 404);
        }

        $this->validate($request, [
            'nama' => 'required|string|max:255',
            'gambar' => 'required|string',
        ]);

        $template = ProductDesignTemplate::create([
            'produk_id' => $product->id,
            'nama' => $request->input('nama'),
            'gambar' => $request->input('gambar'),
            'urutan' => $product->designTemplates()->count(),
        ]);

        return $this->successResponse($template, 'Template desain berhasil ditambahkan', 201);
    }

    /**
     * Remove a design template option from a product.
     */
    public function destroy(string $productId, string $templateId)
    {
        $template = ProductDesignTemplate::where('produk_id', $productId)->find($templateId);

        if (!$template) {
            return $this->errorResponse('Template desain tidak ditemukan', 404);
        }

        $template->delete();

        return $this->successResponse(null, 'Template desain berhasil dihapus');
    }
}
