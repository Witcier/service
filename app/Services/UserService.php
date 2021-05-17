<?php

namespace App\Services;

class UserService
{
    public function store($username, $password, $realname, $status, $remark)
    {
        $user = new User([
            'username' => $username,
            'password' => $password,
            'realname' => $realname,
            'status' => $status,
            'remark' => $remark,
        ]);

        $user->save();
    }
}