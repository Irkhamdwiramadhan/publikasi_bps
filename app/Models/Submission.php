<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    // Izinkan semua kolom untuk diisi (untuk kemudahan)
    protected $guarded = [];

    /**
     * Relasi ke User (Penyusun).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Publication (Master Publikasi).
     */
    public function publication()
    {
        return $this->belongsTo(Publication::class);
    }

    /**
     * Relasi ke User (Pembuat Cover).
     */
    public function pembuatCover()
    {
        return $this->belongsTo(User::class, 'pembuat_cover_id');
    }
}