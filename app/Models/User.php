<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // <-- Import Trait Spatie

// [REVISI] Import model-model yang akan kita hubungkan
use App\Models\Sprp;
use App\Models\SubmissionPublication;
use App\Models\SubmissionComment;

class User extends Authenticatable // implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles; // <-- Gunakan Trait HasRoles

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email', // Kita tetap pakai email untuk login Admin
        'nip_bps', // NIP untuk login Pegawai
        'password',
        'status', // Pastikan 'status' ada di sini
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi ke Sprp (Seorang user bisa memiliki banyak SPRP)
     */
    public function sprps()
    {
        return $this->hasMany(Sprp::class);
    }

    // ==========================================================
    // [TAMBAHAN] RELASI UNTUK FITUR ALUR KERJA & KOMENTAR
    // ==========================================================

    /**
     * Relasi ke SubmissionPublication (Satu user bisa membuat banyak pengajuan)
     */
    public function submissionPublications()
    {
        return $this->hasMany(SubmissionPublication::class);
    }

    /**
     * Relasi ke SubmissionComment (Satu user bisa menulis banyak komentar)
     */
    public function submissionComments()
    {
        return $this->hasMany(SubmissionComment::class);
    }
}

