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

        $addresses = ShippingAddress::where('pengguna_id', $user->id)
            ->orderBy('utama', 'desc')
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
            'nama_penerima' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
            'utama' => 'nullable|boolean',
        ]);

        // If this is set as default, remove default from others
        if ($request->boolean('utama')) {
            ShippingAddress::where('pengguna_id', $user->id)
                ->update(['utama' => false]);
        }

        $address = ShippingAddress::create([
            'pengguna_id' => $user->id,
            'nama_penerima' => $request->input('nama_penerima'),
            'telepon' => $request->input('telepon'),
            'alamat' => $request->input('alamat'),
            'kota' => $request->input('kota'),
            'provinsi' => $request->input('provinsi'),
            'kode_pos' => $request->input('kode_pos'),
            'utama' => $request->boolean('utama'),
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
            ->where('pengguna_id', $user->id)
            ->first();

        if (!$address) {
            return $this->errorResponse('Alamat tidak ditemukan', 404);
        }

        $this->validate($request, [
            'nama_penerima' => 'sometimes|required|string|max:255',
            'telepon' => 'sometimes|required|string|max:20',
            'alamat' => 'sometimes|required|string',
            'kota' => 'sometimes|required|string|max:100',
            'provinsi' => 'sometimes|required|string|max:100',
            'kode_pos' => 'sometimes|required|string|max:10',
            'utama' => 'nullable|boolean',
        ]);

        // If this is set as default, remove default from others
        if ($request->boolean('utama')) {
            ShippingAddress::where('pengguna_id', $user->id)
                ->where('id', '!=', $id)
                ->update(['utama' => false]);
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
            ->where('pengguna_id', $user->id)
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
            ->where('pengguna_id', $user->id)
            ->first();

        if (!$address) {
            return $this->errorResponse('Alamat tidak ditemukan', 404);
        }

        // Remove default from all other addresses
        ShippingAddress::where('pengguna_id', $user->id)
            ->update(['utama' => false]);

        // Set this one as default
        $address->utama = true;
        $address->save();

        return $this->successResponse($address, 'Alamat default berhasil diubah');
    }
}
