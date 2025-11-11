<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionPublication extends Model
{
    use HasFactory;

    /**
     * Nama tabel
     */
    protected $table = 'submission_publications';

    /**
     * Kolom yang boleh diisi
     */
    protected $fillable = [
        'user_id',
        'catalog_id',
        
        'judul_publikasi', // Nama baru dari migrasi
        'type_publikasi',
        'judul_eng',
        'estimasi_rilis',
        'bahasa',
        'issn',
        'isbn',
        
        'fungsi_pengusul',
        'tautan_publikasi', 
        'link_publikasi_final', // <-- Ditambahkan dari migrasi
        'spnrs_ketua_tim',
        'status',
    ];

    /**
     * Casts untuk kolom tanggal
     */
    protected $casts = [
        'estimasi_rilis' => 'date',
    ];

    /**
     * Relasi ke User (Penyusun)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Katalog
     */
    public function catalog()
    {
        return $this->belongsTo(Catalog::class, 'catalog_id');
    }

    /**
     * Relasi ke SPRP (Satu Submission punya satu SPRP)
     */
    public function sprp()
    {
        return $this->hasOne(Sprp::class, 'submission_publication_id');
    }

    /**
     * Relasi ke SPNSR (Satu Submission punya satu SPNSR)
     */
    public function spnsrSubmission()
    {
        return $this->hasOne(SpnsrSubmission::class, 'submission_publication_id');
    }

    /**
     * ▼▼▼ INI FUNGSI YANG HILANG (PENYEBAB ERROR) ▼▼▼
     *
     * Relasi ke Komentar (SubmissionComment)
     */
    public function comments()
    {
        // Asumsi nama model komentar Anda adalah SubmissionComment
        // dan foreign key-nya adalah 'submission_publication_id'
        return $this->hasMany(\App\Models\SubmissionComment::class, 'submission_publication_id');
    }
}