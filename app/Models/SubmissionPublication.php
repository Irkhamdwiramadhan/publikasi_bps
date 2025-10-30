<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne; // 👈 1. Import HasOne

// 2. Import model-model relasi (Best practice)
use App\Models\User;
use App\Models\Publication;
use App\Models\SubmissionComment;
use App\Models\SpnsrSubmission; // 👈 3. Import SpnsrSubmission

class SubmissionPublication extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     * (Otomatis: submission_publications)
     */
    // protected $table = 'submission_publications';

    protected $fillable = [
        'publication_id',
        'user_id',
        'fungsi_pengusul',
        'tautan_publikasi',
        'spnrs_ketua_tim',
        'status',
    ];

    /**
     * Relasi ke User (Penyusun).
     */
    public function user(): BelongsTo
    {
        // 4. Lebih baik eksplisit mencantumkan foreign key
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Publication (Master).
     */
    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class, 'publication_id');
    }

    /**
     * Relasi ke Komentar.
     */
    public function comments(): HasMany
    {
        // 5. Asumsi foreign key adalah 'submission_publication_id'
        return $this->hasMany(SubmissionComment::class, 'submission_publication_id');
    }

    /**
     * ===================================================================
     * REVISI UTAMA:
     * Relasi ke SPNSR yang terkait LANGSUNG dengan pengajuan ini.
     * * Ini adalah kunci untuk memperbaiki masalah data ganda antar tahun.
     * ===================================================================
     */
    public function spnsrSubmission(): HasOne // 👈 6. Tambahkan relasi baru
    {
        // Model ini (SubmissionPublication) memiliki satu SpnsrSubmission
        // melalui foreign key 'submission_publication_id' di tabel 'spnsr_submissions'.
        return $this->hasOne(SpnsrSubmission::class, 'submission_publication_id');
    }
}

