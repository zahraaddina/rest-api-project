
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokters', function (Blueprint $table) {
            $table->id();

            $table->foreignId('poli_id')
                  ->constrained('polis')
                  ->restrictOnDelete();

            $table->string('kode_dokter', 20)->unique();
            $table->string('nama_dokter', 100);
            $table->string('spesialisasi', 100);
            $table->string('no_telepon', 20)->nullable();

            $table->enum('status', ['Aktif', 'Nonaktif'])
                  ->default('Aktif');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokters');
    }
};
