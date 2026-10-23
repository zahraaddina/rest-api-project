<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class DokterController extends Controller
{
    // GET - Menampilkan semua dokter
    public function index(): JsonResponse
    {
        $dokters = Dokter::with('poli')
            ->orderBy('nama_dokter')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data dokter berhasil diambil',
            'data' => $dokters
        ], 200);
    }

    // POST - Menambahkan dokter baru
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'poli_id' => [
                'required',
                'integer',
                'exists:polis,id'
            ],
            'kode_dokter' => [
                'required',
                'string',
                'max:20',
                'unique:dokters,kode_dokter'
            ],
            'nama_dokter' => [
                'required',
                'string',
                'max:100'
            ],
            'spesialisasi' => [
                'required',
                'string',
                'max:100'
            ],
            'no_telepon' => [
                'nullable',
                'string',
                'max:20'
            ],
            'status' => [
                'required',
                Rule::in(['Aktif', 'Nonaktif'])
            ]
        ]);

        $dokter = Dokter::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Dokter berhasil ditambahkan',
            'data' => $dokter->load('poli')
        ], 201);
    }

    // GET - Menampilkan detail dokter
    public function show(string $id): JsonResponse
    {
        $dokter = Dokter::with([
            'poli',
            'jadwalPraktiks'
        ])->find($id);

        if (!$dokter) {
            return response()->json([
                'success' => false,
                'message' => 'Dokter tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail dokter berhasil diambil',
            'data' => $dokter
        ], 200);
    }

    // PUT - Memperbarui data dokter
    public function update(
        Request $request,
        string $id
    ): JsonResponse {
        $dokter = Dokter::find($id);

        if (!$dokter) {
            return response()->json([
                'success' => false,
                'message' => 'Dokter tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'poli_id' => [
                'required',
                'integer',
                'exists:polis,id'
            ],
            'kode_dokter' => [
                'required',
                'string',
                'max:20',
                Rule::unique('dokters', 'kode_dokter')
                    ->ignore($dokter->id)
            ],
            'nama_dokter' => [
                'required',
                'string',
                'max:100'
            ],
            'spesialisasi' => [
                'required',
                'string',
                'max:100'
            ],
            'no_telepon' => [
                'nullable',
                'string',
                'max:20'
            ],
            'status' => [
                'required',
                Rule::in(['Aktif', 'Nonaktif'])
            ]
        ]);

        $dokter->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data dokter berhasil diperbarui',
            'data' => $dokter->load('poli')
        ], 200);
    }

    // DELETE - Menghapus dokter
    public function destroy(string $id): JsonResponse
    {
        $dokter = Dokter::find($id);

        if (!$dokter) {
            return response()->json([
                'success' => false,
                'message' => 'Dokter tidak ditemukan'
            ], 404);
        }

        if ($dokter->jadwalPraktiks()->exists()) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Dokter masih memiliki jadwal praktik'
            ], 409);
        }

        $dokter->delete();

        return response()->json([
            'success' => true,
            'message' => 'Dokter berhasil dihapus'
        ], 200);
    }
}
