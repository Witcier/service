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

    protected $appends = [
        'permission_url'
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

    // 定义一个访问器，获取所有祖先类目并按层级排序
    public function getAncestorsAttribute()
    {
        return Rule::query()
            // 使用上面的访问器获取所有祖先类目 ID
            ->where('id', $this->parent_id)
            // 按层级排序
            ->orderBy('level')
            ->get();
    }

    // 定义一个访问器，获取以 - 为分隔的所有祖先类目名称以及当前类目的名称
    public function getPermissionUrlAttribute()
    {
        return $this->ancestors  // 获取所有祖先类目
                    ->pluck('name') // 取出所有祖先类目的 name 字段作为一个数组
                    ->push($this->name) // 将当前类目的 name 字段值加到数组的末尾
                    ->implode('/'); // 用 - 符号将数组的值组装成一个字符串
    }
}
