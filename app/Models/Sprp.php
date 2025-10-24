<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sprp extends Model
{
    use HasFactory;

    /**
     * Properti $fillable menentukan kolom mana yang boleh diisi
     * secara massal (mass assignable).
     */
    protected $fillable = [
        'user_id',
        'publication_id',
        'jumlah_romawi',
        'jumlah_arab',
        'kategori',
        'isbn',
        'issn',
        'pembuat_cover', // <-- INI ADALAH PERBAIKANNYA
        'orientasi',
        'diterbitkan_untuk',
        'ukuran_kertas',
        'status',
        'nomor_publikasi_final',
    ];

    /**
     * Relasi ke model User (Penyusun).
     * ('user_id')
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke model Publication (Judul Master).
     * ('publication_id')
     */
    public function publication()
    {
        return $this->belongsTo(Publication::class);
    }
}

