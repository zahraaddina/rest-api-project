<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // POST - Login admin
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $token = Auth::guard('api')->attempt($validated);

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => Auth::guard('api')->user(),
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => Auth::guard('api')
                    ->factory()->getTTL() * 60
            ]
        ], 200);
    }

    // GET - Profil admin yang login
    public function me(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Profil admin berhasil diambil',
            'data' => Auth::guard('api')->user()
        ], 200);
    }

    // POST - Logout admin
    public function logout(): JsonResponse
    {
        Auth::guard('api')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ], 200);
    }
}
