<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use Illuminate\Http\JsonResponse;

class PoliController extends Controller
{
    public function index(): JsonResponse
    {
        $polis = Poli::orderBy('nama_poli')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar poliklinik berhasil diambil',
            'data' => $polis
        ], 200);
    }
}
