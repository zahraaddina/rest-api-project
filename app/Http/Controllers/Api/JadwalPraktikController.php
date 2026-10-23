<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JadwalPraktik;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class JadwalPraktikController extends Controller
{
    // GET - Menampilkan seluruh jadwal
    public function index(): JsonResponse
    {
        $jadwals = JadwalPraktik::with('dokter.poli')
            ->orderBy('id')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar jadwal berhasil diambil',
            'data' => $jadwals
        ], 200);
    }

    // POST - Menambahkan jadwal praktik
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'dokter_id' => [
                'required',
                'integer',
                Rule::exists('dokters', 'id')
                    ->where('status', 'Aktif')
            ],
            'hari' => [
                'required',
                Rule::in([
                    'Senin', 'Selasa', 'Rabu',
                    'Kamis', 'Jumat', 'Sabtu', 'Minggu'
                ])
            ],
            'jam_mulai' => [
                'required',
                'date_format:H:i'
            ],
            'jam_selesai' => [
                'required',
                'date_format:H:i',
                'after:jam_mulai'
            ],
            'status' => [
                'required',
                Rule::in(['Aktif', 'Nonaktif'])
            ]
        ]);

        // Cek apakah jadwal dokter bertabrakan
        $bentrok = JadwalPraktik::where(
            'dokter_id',
            $validated['dokter_id']
        )
            ->where('hari', $validated['hari'])
            ->where('status', 'Aktif')
            ->where('jam_mulai', '<', $validated['jam_selesai'])
            ->where('jam_selesai', '>', $validated['jam_mulai'])
            ->exists();

        if ($validated['status'] === 'Aktif' && $bentrok) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal praktik dokter bertabrakan'
            ], 422);
        }

        $jadwal = JadwalPraktik::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal praktik berhasil ditambahkan',
            'data' => $jadwal->load('dokter.poli')
        ], 201);
    }

    // GET - Menampilkan detail jadwal
    public function show(string $id): JsonResponse
    {
        $jadwal = JadwalPraktik::with('dokter.poli')
            ->find($id);

        if (!$jadwal) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal praktik tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail jadwal berhasil diambil',
            'data' => $jadwal
        ], 200);
    }

    // PUT - Memperbarui jadwal praktik
    public function update(
        Request $request,
        string $id
    ): JsonResponse {
        $jadwal = JadwalPraktik::find($id);

        if (!$jadwal) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal praktik tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'dokter_id' => [
                'required',
                'integer',
                Rule::exists('dokters', 'id')
                    ->where('status', 'Aktif')
            ],
            'hari' => [
                'required',
                Rule::in([
                    'Senin', 'Selasa', 'Rabu',
                    'Kamis', 'Jumat', 'Sabtu', 'Minggu'
                ])
            ],
            'jam_mulai' => [
                'required',
                'date_format:H:i'
            ],
            'jam_selesai' => [
                'required',
                'date_format:H:i',
                'after:jam_mulai'
            ],
            'status' => [
                'required',
                Rule::in(['Aktif', 'Nonaktif'])
            ]
        ]);

        // Abaikan jadwal yang sedang diperbarui
        $bentrok = JadwalPraktik::where(
            'dokter_id',
            $validated['dokter_id']
        )
            ->where('hari', $validated['hari'])
            ->where('status', 'Aktif')
            ->where('id', '!=', $jadwal->id)
            ->where('jam_mulai', '<', $validated['jam_selesai'])
            ->where('jam_selesai', '>', $validated['jam_mulai'])
            ->exists();

        if ($validated['status'] === 'Aktif' && $bentrok) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal praktik dokter bertabrakan'
            ], 422);
        }

        $jadwal->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal praktik berhasil diperbarui',
            'data' => $jadwal->load('dokter.poli')
        ], 200);
    }

    // DELETE - Menghapus jadwal
    public function destroy(string $id): JsonResponse
    {
        $jadwal = JadwalPraktik::find($id);

        if (!$jadwal) {
            return response()->json([
                'success' => false,
                'message' => 'Jadwal praktik tidak ditemukan'
            ], 404);
        }

        $jadwal->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal praktik berhasil dihapus'
        ], 200);
    }
}
