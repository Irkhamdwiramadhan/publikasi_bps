<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // TAMBAHKAN ARRAY $fillable INI:
    protected $fillable = [
        'title_ind',
        
        'publication_type',
        'catalog_number',
        'year',
        'frequency',
        'issn_number',
        
        // Kita tidak perlu 'region_code' dan 'language' karena Anda sudah hapus
    ];
}