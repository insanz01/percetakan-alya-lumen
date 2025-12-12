<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Get all users (customers)
     */
    public function index(Request $request)
    {
        $query = User::with('addresses');

        // Filter by role
        if ($request->has('role')) {
            $query->where('role', $request->input('role'));
        } else {
            // Default: only customers
            $query->where('role', 'customer');
        }

        // Filter by active status
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        // Search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        // Pagination
        if ($request->has('per_page')) {
            $users = $query->paginate($request->input('per_page', 15));
            return $this->paginatedResponse($users);
        }

        return $this->successResponse($query->get());
    }

    /**
     * Get single user
     */
    public function show($id)
    {
        $user = User::with(['addresses', 'orders'])->find($id);

        if (!$user) {
            return $this->errorResponse('User tidak ditemukan', 404);
        }

        return $this->successResponse($user);
    }

    /**
     * Create new user
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string',
            'role' => 'nullable|in:customer,admin,super_admin',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'phone' => $request->input('phone'),
            'role' => $request->input('role', 'customer'),
            'is_active' => true,
        ]);

        return $this->successResponse($user, 'User berhasil dibuat', 201);
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->errorResponse('User tidak ditemukan', 404);
        }

        $this->validate($request, [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'is_active']);

        if ($request->has('password') && $request->input('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $user->update($data);

        return $this->successResponse($user, 'User berhasil diupdate');
    }

    /**
     * Delete user
     */
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->errorResponse('User tidak ditemukan', 404);
        }

        $user->delete();

        return $this->successResponse(null, 'User berhasil dihapus');
    }

    /**
     * Get user statistics
     */
    public function statistics()
    {
        $thisMonth = Carbon::now()->startOfMonth();

        $stats = [
            'total_customers' => User::where('role', 'customer')->count(),
            'active_customers' => User::where('role', 'customer')->where('is_active', true)->count(),
            'new_customers_this_month' => User::where('role', 'customer')->where('created_at', '>=', $thisMonth)->count(),
        ];

        return $this->successResponse($stats);
    }
}
