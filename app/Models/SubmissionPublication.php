<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionPublication extends Model
{
    use HasFactory;

    protected $fillable = [
        'publication_id',
        'user_id',
        'fungsi_pengusul',
        'tautan_publikasi',
        'spnrs_ketua_tim',
        'status',
    ];

    // Relasi ke tabel publications
    public function publication()
    {
        return $this->belongsTo(Publication::class);
    }

    // Relasi ke user (penyusun)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // [REVISI TOTAL] Relasi ke komentar
    public function comments()
    {
        // 1. Arahkan ke model 'SubmissionComment' yang benar
        // 2. Tentukan foreign key 'submission_publication_id'
        // 3. Urutkan dari yang terbaru (latest) agar chat-nya benar
        return $this->hasMany(SubmissionComment::class, 'submission_publication_id')->latest();
    }

}
