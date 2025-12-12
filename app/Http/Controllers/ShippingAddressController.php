<?php

namespace App\Http\Controllers;

use App\Models\ShippingAddress;
use Illuminate\Http\Request;

class ShippingAddressController extends Controller
{
    /**
     * Get user's shipping addresses
     */
    public function index(Request $request)
    {
        $user = $request->auth;

        $addresses = ShippingAddress::where('user_id', $user->id)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->successResponse($addresses);
    }

    /**
     * Create new shipping address
     */
    public function store(Request $request)
    {
        $user = $request->auth;

        $this->validate($request, [
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'is_default' => 'nullable|boolean',
        ]);

        // If this is set as default, remove default from others
        if ($request->boolean('is_default')) {
            ShippingAddress::where('user_id', $user->id)
                ->update(['is_default' => false]);
        }

        $address = ShippingAddress::create([
            'user_id' => $user->id,
            'recipient_name' => $request->input('recipient_name'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'province' => $request->input('province'),
            'postal_code' => $request->input('postal_code'),
            'is_default' => $request->boolean('is_default'),
        ]);

        return $this->successResponse($address, 'Alamat berhasil ditambahkan', 201);
    }

    /**
     * Update shipping address
     */
    public function update(Request $request, $id)
    {
        $user = $request->auth;

        $address = ShippingAddress::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$address) {
            return $this->errorResponse('Alamat tidak ditemukan', 404);
        }

        $this->validate($request, [
            'recipient_name' => 'sometimes|required|string|max:255',
            'phone' => 'sometimes|required|string|max:20',
            'address' => 'sometimes|required|string',
            'city' => 'sometimes|required|string|max:100',
            'province' => 'sometimes|required|string|max:100',
            'postal_code' => 'sometimes|required|string|max:10',
            'is_default' => 'nullable|boolean',
        ]);

        // If this is set as default, remove default from others
        if ($request->boolean('is_default')) {
            ShippingAddress::where('user_id', $user->id)
                ->where('id', '!=', $id)
                ->update(['is_default' => false]);
        }

        $address->update($request->all());

        return $this->successResponse($address, 'Alamat berhasil diupdate');
    }

    /**
     * Delete shipping address
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->auth;

        $address = ShippingAddress::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$address) {
            return $this->errorResponse('Alamat tidak ditemukan', 404);
        }

        $address->delete();

        return $this->successResponse(null, 'Alamat berhasil dihapus');
    }

    /**
     * Set address as default
     */
    public function setDefault(Request $request, $id)
    {
        $user = $request->auth;

        $address = ShippingAddress::where('id', $id)
            ->where('user_id', $user->id)
            ->first();

        if (!$address) {
            return $this->errorResponse('Alamat tidak ditemukan', 404);
        }

        // Remove default from all other addresses
        ShippingAddress::where('user_id', $user->id)
            ->update(['is_default' => false]);

        // Set this one as default
        $address->is_default = true;
        $address->save();

        return $this->successResponse($address, 'Alamat default berhasil diubah');
    }
}
