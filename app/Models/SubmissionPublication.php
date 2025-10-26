<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionPublication extends Model
{
    use HasFactory;

    // Tambahkan/pastikan 'fillable' Anda ada
    protected $fillable = [
        'publication_id',
        'user_id',
        'fungsi_pengusul',
        'tautan_publikasi',
        'spnrs_ketua_tim',
        'status',
    ];

    /**
     * [FIX 1] Tambahkan relasi ke Publication
     * Ini akan menghubungkan 'publication_id' ke tabel 'publications'
     */
    public function publication()
    {
        return $this->belongsTo(Publication::class);
    }

    /**
     * [FIX 2] Tambahkan relasi ke User (Penyusun)
     * Ini akan menghubungkan 'user_id' ke tabel 'users'
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * [Opsional] Relasi ke Komentar (jika Anda perlukan)
     */
    public function comments()
    {
        return $this->hasMany(SubmissionComment::class);
    }
}
