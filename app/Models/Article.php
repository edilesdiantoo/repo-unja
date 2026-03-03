<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    // File: app/Models/Article.php
    protected $fillable = [
        'user_id', 'title', 'author', 'abstract', 'keywords',
        'study_program', 'year', 'document_type', 'pembimbing_1',
        'pembimbing_2', 'accreditation_level', 'access_type',
        'pdf_file', 'cover_image', 'catatan_revisi', 'verified_by',
        'pesan_revisi_user', 'status',

    ];

    // TAMBAHKAN FUNGSI INI
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
