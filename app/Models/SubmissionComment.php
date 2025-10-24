<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionComment extends Model
{
    use HasFactory;

    /**
     * [REVISI] Atribut yang boleh diisi
     */
    protected $fillable = [
        'submission_publication_id', // Diperbarui
        'user_id',
        'body',
    ];

    /**
     * Relasi: Komentar ini milik siapa (Pengguna).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * [REVISI] Relasi: Komentar ini untuk Pengajuan mana.
     * Kita ubah nama fungsi & foreign key
     */
    public function submissionPublication()
    {
        // (Model, Foreign Key)
        return $this->belongsTo(SubmissionPublication::class, 'submission_publication_id');
    }
}

