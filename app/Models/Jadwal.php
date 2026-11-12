<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Jadwal extends Model
{
    use HasFactory;

    protected $fillable = [
        'kelas_id',
        'pengajar_id',
        'hari',
        'jam',
        'ruang',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function pengajar(): BelongsTo
    {
        return $this->belongsTo(Pengajar::class);
    }
}
