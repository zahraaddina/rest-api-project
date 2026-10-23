<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@rs.test'
            ],
            [
                'name' => 'Admin Rumah Sakit',
                'password' => 'Admin12345!'
            ]
        );
    }
}
