<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

// REVISI DI SINI: Hapus 's' di akhir
class SpnsrSubmission extends Model 
{
    use HasFactory;

    // Nama tabel akan otomatis dianggap 'spnsr_submissions' (plural) oleh Laravel,
    // jadi tidak perlu $table jika nama tabel Anda sudah benar.
    // Jika nama tabel berbeda, tambahkan: protected $table = 'nama_tabel_anda';

    protected $fillable = [
        'user_id',
        'nomor_surat',
        'tanggal_prosa',
        'judul_publikasi',
        'tipe_arc',
        'keterangan',
        'status',
    ];

    /**
     * Relasi ke user (Penyusun).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}