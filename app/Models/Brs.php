<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brs extends Model
{
    use HasFactory;

    /**
     * Tentukan field yang boleh diisi (mass assignable)
     */
    protected $fillable = [
        'judul',
        'bulan',
        'user_id',
        'pdf_path',
        'infografis_paths',
        'zip_path',
        'excel_path',
        'nomor_brs',
    ];

    /**
     * PENTING: Otomatis cast kolom 'infografis_paths'
     * dari JSON (database) ke Array (PHP) dan sebaliknya.
     */
    protected $casts = [
        'infografis_paths' => 'array',
        'bulan' => 'date', // Cast 'bulan' sebagai objek Carbon/Date
    ];

    /**
     * Definisikan relasi ke User (Pengelola)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}