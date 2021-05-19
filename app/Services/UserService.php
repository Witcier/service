<?php

namespace App\Services;

use App\Models\Service;
use App\Models\User;

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
            $user->groupAttach($user, $groupIds);
        });
    }
}