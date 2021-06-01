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

    public function getAllPermissions()
    {
        $permissions = $this->userGroupPermissions->load('groupPermissions');
        $permission_url = [];

        foreach ($permissions as $permission) {
            foreach ($permission->groupPermissions as $groupPermission) {
                $permission_url[] = $groupPermission->permission_url;
            }           
        }

        return $permission_url = array_unique($permission_url);
    }
}
