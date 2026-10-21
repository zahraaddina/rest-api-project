<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poli extends Model
{
    protected $table = 'polis';

    protected $fillable = [
        'kode_poli',
        'nama_poli',
        'lokasi',
        'status',
    ];

    public function dokters(): HasMany
    {
        return $this->hasMany(Dokter::class, 'poli_id');
    }
}
