<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends Controller
{
    /**
     * Register new user
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'phone' => $request->input('phone'),
            'role' => 'customer',
            'is_active' => true,
        ]);

        $token = $this->generateToken($user);

        return $this->successResponse([
            'user' => $user,
            'token' => $token,
        ], 'Registrasi berhasil', 201);
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return $this->errorResponse('Email atau password salah', 401);
        }

        if (!$user->is_active) {
            return $this->errorResponse('Akun Anda tidak aktif', 403);
        }

        $token = $this->generateToken($user);

        return $this->successResponse([
            'user' => $user,
            'token' => $token,
        ], 'Login berhasil');
    }

    /**
     * Admin login
     */
    public function adminLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return $this->errorResponse('Email atau password salah', 401);
        }

        if (!$user->isAdmin()) {
            return $this->errorResponse('Anda tidak memiliki akses admin', 403);
        }

        if (!$user->is_active) {
            return $this->errorResponse('Akun Anda tidak aktif', 403);
        }

        $token = $this->generateToken($user);

        return $this->successResponse([
            'user' => $user,
            'token' => $token,
        ], 'Login berhasil');
    }

    /**
     * Get current user
     */
    public function me(Request $request)
    {
        $user = $request->auth;

        if (!$user) {
            return $this->errorResponse('Unauthorized', 401);
        }

        return $this->successResponse($user->load('addresses'));
    }

    /**
     * Update profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->auth;

        if (!$user) {
            return $this->errorResponse('Unauthorized', 401);
        }

        $this->validate($request, [
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'nullable|string',
            'avatar' => 'nullable|string',
        ]);

        $user->update($request->only(['name', 'phone', 'avatar']));

        return $this->successResponse($user, 'Profil berhasil diupdate');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $user = $request->auth;

        if (!$user) {
            return $this->errorResponse('Unauthorized', 401);
        }

        $this->validate($request, [
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return $this->errorResponse('Password saat ini salah', 400);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return $this->successResponse(null, 'Password berhasil diubah');
    }

    /**
     * Logout (just for API consistency, actual logout handled on client)
     */
    public function logout()
    {
        return $this->successResponse(null, 'Logout berhasil');
    }

    /**
     * Generate JWT token
     */
    private function generateToken($user)
    {
        $payload = [
            'iss' => 'percetakan-api',
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24 * 7), // 7 days
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ];

        return JWT::encode($payload, env('JWT_SECRET', 'your-secret-key'), 'HS256');
    }
}
