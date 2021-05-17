<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'rules',
        'pid',
        'type',
        'remark',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
