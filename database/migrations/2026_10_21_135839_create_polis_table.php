
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('polis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_poli', 20)->unique();
            $table->string('nama_poli', 100);
            $table->string('lokasi', 100);
            $table->enum('status', ['Aktif', 'Nonaktif'])
                  ->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('polis');
    }
};
