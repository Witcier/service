<?php

namespace App\Services;

use App\Models\Service;
use App\Models\User;
use Jiannei\Response\Laravel\Support\Facades\Response;

class UserService
{
    public function store($user, $groupIds)
    {
        $user = \DB::transaction(function () use ($user, $groupIds) {
            // 创建一个用户
            $user = new User($user);
            $user->save();
            
            // 同时创建一个客服
            $service = new Service([
                'username' => $user->username,
                'nick' => $user->realname,
                'platform_id' => $user->platform_id,
            ]);

            $service->user()->associate($user);
            $service->save();

            // 创建用户组的关联
            $user->userGroupPermissions()->attach($groupIds);
        });
    }

    public function update(User $user, $userData, $groupIds)
    {
        // 更新用户数据
        $user->update($userData);

        // 更新客服信息
        $user->service->update([
            'username' => $user->username,
            'nick' => $user->realname,
            'platform_id' => $user->platform_id,
        ]);

        // 更新用户组
        if (!empty($groupIds) && is_array($groupIds)) {
            // 删除之前的用户组的关联
            $user->userGroupPermissions()->detach();

            // 更新用户组的关联
            $user->userGroupPermissions()->attach($groupIds);
        }
    }
}