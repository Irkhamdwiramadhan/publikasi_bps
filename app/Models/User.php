<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles; // <-- Import Trait Spatie
use App\Models\Sprp;
use App\Models\SubmissionPublication;
use App\Models\SubmissionComment;

/**
 * [REVISI] Menambahkan PHPDoc (DocBlock) untuk Intelephense.
 * Ini akan "memberi tahu" VS Code tentang metode-metode dari Spatie
 * (seperti hasRole) dan menghilangkan notifikasi "Problem".
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @method bool hasRole($role, $guard = null)
 * @method bool hasAnyRole($roles, $guard = null)
 * @method bool hasAllRoles($roles, $guard = null)
 * @method \Spatie\Permission\Models\Role assignRole($role)
 * @method \Spatie\Permission\Models\Role removeRole($role)
 * @method \Spatie\Permission\Models\Role syncRoles($roles)
 */
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

    /**
     * Relasi ke Pengajuan Publikasi
     */
    public function submissionPublications()
    {
        return $this->hasMany(SubmissionPublication::class);
    }

    /**
     * Relasi ke Komentar
     */
    public function submissionComments()
    {
        return $this->hasMany(SubmissionComment::class);
    }
}

