<?php

namespace App\Models;

use Hash;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'phone',
        'password',
        'realname',
        'platform_id',
        'remark',
        'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->password) {
                $model->password = Hash::make($model->password);
            }
        });
    }

    public function service()
    {
        return $this->hasOne(Service::class);
    }

    public function userGroupPermissions()
    {
        return $this->belongsToMany(Group::class, 'user_group_permissions')
            ->withTimestamps()
            ->orderBy('user_group_permissions.created_at', 'desc');
    }

    public function getUserPermissions()
    {
        $permissions = $this->userGroupPermissions->load('groupPermissions');
        $ruleIds = [];

        foreach ($permissions as $permission) {
            foreach ($permission->groupPermissions as $groupPermission) {
                $ruleIds[] = $groupPermission->id;
            }           
        }

        $ruleIds = array_unique($ruleIds);

        return $this->getPermissionUrl(Rule::whereIn('id', $ruleIds)->get());
    }

    public function getPermissionUrl($permission = null, $parentId = null, $preName = null) {
        if (is_null($permission)) {
            // 从数据库中一次性取出所有类目
            $permission = Rule::all();
        }

        $all = $permission
            // 从所有类目中挑选出父类目 ID 为 $parentId 的类目
            ->where('parent_id', $parentId)
            // 遍历这些类目，并用返回值构建一个新的集合
            ->map(function (Rule $rule) use ($permission, $preName) {
                $preName = $preName ? $preName . '/' . $rule->name : $rule->name;
                if ($rule->level === 3) {
                    $data = ['permission_url' => $preName];
                }
                
                $data[] = $this->getPermissionUrl($permission, $rule->id, $preName);

                return $data;
            });

        $result = [];
        array_walk_recursive($all, function($value) use (&$result) {
        array_push($result, $value);
        });

        return $result;
    }
}
