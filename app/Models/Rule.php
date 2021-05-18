<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    use HasFactory;

    const TYPE_MODULE = 1;
    const TYPE_CONTROLLER = 2;
    const TYPE_OPERATION = 3;
    
    public static $typeMap = [
        self::TYPE_MODULE => '模块',
        self::TYPE_CONTROLLER => '控制器',
        self::TYPE_OPERATION => '操作',
    ];

    protected $fillable = [
        'title',
        'name',
        'level',
        'parent_id',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Rule $rule) {
            if (is_null($rule->parent_id)) {
                $rule->level = Rule::TYPE_MODULE;
            } else {
                $rule->level = $rule->parent->level + 1;
            }
        });
    }

    public function parent()
    {
        return $this->belongsTo(Rule::class);
    }

    public function children()
    {
        return $this->hasMany(Rule::class, 'parent_id');
    }
}
