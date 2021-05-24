<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'pid',
        'type',
        'remark',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(Group::class);
    }

    public function children()
    {
        return $this->hasMany(Group::class, 'parent_id');
    }

    public function groupPermissions()
    {
        return $this->belongsToMany(Rule::class, 'group_permissions')
            ->withTimestamps()
            ->orderBy('group_permissions.created_at', 'desc');
    }
}
