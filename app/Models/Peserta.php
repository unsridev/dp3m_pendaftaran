<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peserta extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kelas_id',
        'nik',
        'nidn',
        'nama',
        'hp',
        'email',
        'tipe',
        'status',
        'bukti',
        'alasan',
        'el_username',
        'el_password',
        'wa',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function isVerified(): bool
    {
        return $this->status === 'Terverifikasi';
    }

    public function isPending(): bool
    {
        return $this->status === 'Menunggu Verifikasi';
    }

    public function isRejected(): bool
    {
        return $this->status === 'Ditolak';
    }
}
