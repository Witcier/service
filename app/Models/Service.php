<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'username',
        'nick',
        'birth',
        'email',
        'mobile',
        'sex',
        'photo',
        'is_service',
        'platform_id',
        'status',
        'mark_time',
        'is_auto_reply',
        'auto_reply_content',
        'area',
        'note_id',
    ];

    protected $casts = [
        'sex' => 'boolean',
        'is_service' => 'boolean',
        'is_auto_reply' => 'boolean',
    ];

    protected $dates = [
        'birth'
    ];
}
