<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrsComment extends Model
{
    use HasFactory;

    protected $fillable = ['brs_id', 'user_id', 'body', 'role', 'is_read'];

    public function brs()
    {
        return $this->belongsTo(Brs::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}