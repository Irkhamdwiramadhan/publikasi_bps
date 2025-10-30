<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User; 
use App\Models\SubmissionPublication; // 👈 Penting: Import model relasi baru

// REVISI: Nama class disesuaikan dengan nama file (SpnsrSubmission, bukan SpnsrSubmissions)
class SpnsrSubmission extends Model 
{
    use HasFactory;

    /**
     * Nama tabel di database.
     * (Otomatis: spnsr_submissions)
     */
    // protected $table = 'spnsr_submissions';

    /**
     * REVISI: $fillable disesuaikan dengan migrasi baru.
     * Kolom 'publication_id', 'judul_publikasi', 'tipe_arc' dihapus.
     * Kolom 'submission_publication_id' ditambahkan.
     */
    protected $fillable = [
        'user_id',
        'submission_publication_id', // 👈 REVISI: Kunci relasi baru
        'nomor_surat',
        'tanggal_prosa',
        // 'judul_publikasi', // 👈 Dihapus (diambil via relasi)
        // 'tipe_arc', // 👈 Dihapus (diambil via relasi)
        'keterangan',
        'status',
        'signed_spnsr_path', // 👈 Path file TTD Pemimpin
    ];

    /**
     * Relasi ke user (Penyusun).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * REVISI: Relasi diubah dari Publication (Master)
     * ke SubmissionPublication (Pengajuan Publikasi Spesifik)
     */
    public function submissionPublication(): BelongsTo
    {
        // Relasi ini menunjuk ke tabel 'submission_publications'
        return $this->belongsTo(SubmissionPublication::class, 'submission_publication_id');
    }
    
    // REVISI: Hapus relasi 'publication()' yang lama
    // public function publication(): BelongsTo { ... }
}

