<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    // TAMBAHKAN BARIS INI
    protected $fillable = [
        'user_id',
        'description',
    ];

    // Jangan lupa relasi ke User agar Nama User bisa muncul di tabel
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
