<?php

namespace App\Services;

class UserService
{
    public function store($username, $password, $realname, $status, $remark)
    {
        $user = \DB::transaction(function () use ($username, $password, $realname, $status, $remark) {
            // 创建一个用户
            $user = new User([
                'username' => $username,
                'password' => $password,
                'realname' => $realname,
                'status' => $status,
                'remark' => $remark,
            ]);
            $user->save();
            
            // 同时创建一个客服
            
        });
    }
}