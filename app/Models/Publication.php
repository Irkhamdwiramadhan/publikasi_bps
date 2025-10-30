<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\SubmissionPublication; // 👈 Tambahkan import (Best practice)
use App\Models\SpnsrSubmission; // 👈 Tambahkan import (Best practice)

class Publication extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    
    // REVISI: Perbaiki $fillable
    protected $fillable = [
        'title_ind',
        'title_eng', // 👈 TAMBAHKAN INI
        'publication_type',
        'catalog_number',
        'year',
        'frequency',
        'issn_number',
        // 'spnsr_submission_id', // 👈 HAPUS INI (Foreign key tidak ada di tabel ini)
    ];

    /**
     * Relasi ke SEMUA pengajuan publikasi (SubmissionPublication) yang menggunakan master ini.
     */
    public function submissionPublications(): HasMany
    {
        return $this->hasMany(SubmissionPublication::class, 'publication_id');
    }

    /**
     * Relasi ke pengajuan SPNSR (SpnsrSubmission) yang menggunakan master ini.
     * (Asumsi 1 master publikasi hanya punya 1 SPNSR)
     */
    public function spnsrSubmission(): HasOne
    {
        // Relasi ini mencari 'publication_id' di tabel 'spnsr_submissions'
        return $this->hasOne(SpnsrSubmission::class, 'publication_id');
    }
}

