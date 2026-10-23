<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Poli;

class PoliSeeder extends Seeder
{
    public function run(): void
    {
        $polis = [
            [
                'kode_poli' => 'POL001',
                'nama_poli' => 'Poli Umum',
                'lokasi' => 'Lantai 1',
                'status' => 'Aktif'
            ],
            [
                'kode_poli' => 'POL002',
                'nama_poli' => 'Poli Gigi',
                'lokasi' => 'Lantai 2',
                'status' => 'Aktif'
            ],
            [
                'kode_poli' => 'POL003',
                'nama_poli' => 'Poli Anak',
                'lokasi' => 'Lantai 2',
                'status' => 'Aktif'
            ],
            [
                'kode_poli' => 'POL004',
                'nama_poli' => 'Poli Penyakit Dalam',
                'lokasi' => 'Lantai 3',
                'status' => 'Aktif'
            ]
        ];

        foreach ($polis as $poli) {
            Poli::updateOrCreate(
                ['kode_poli' => $poli['kode_poli']],
                $poli
            );
        }
    }
}
