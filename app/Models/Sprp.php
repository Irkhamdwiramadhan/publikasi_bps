<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sprp extends Model
{
    use HasFactory;

    /**
     * Nama tabel (karena nama model 'Sprp' tidak jamak 'Sprps')
     */
    protected $table = 'sprps';

    /**
     * Kolom yang boleh diisi (mass assignable).
     * Perhatikan: 'kategori', 'issn', dan 'isbn' sudah dihapus
     * karena sudah pindah ke tabel submission_publications.
     */
    protected $fillable = [
        'user_id',
        'submission_publication_id', // <-- Ini adalah link baru
        'jumlah_romawi',
        'jumlah_arab',
        'pembuat_cover',
        'orientasi',
        'diterbitkan_untuk',
        'ukuran_kertas',
        'nomor_publikasi_final', // <-- Kolom penting ini tetap ada
        'kategori',
    ];

    /**
     * Relasi ke User (Penyusun)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi baru ke 'jantung' data (submission_publications)
     */
    public function submissionPublication()
    {
        // Menghubungkan model ini ke model SubmissionPublication
        return $this->belongsTo(SubmissionPublication::class, 'submission_publication_id');
    }
}