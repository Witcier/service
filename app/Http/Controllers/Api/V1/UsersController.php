<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\UserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Jiannei\Response\Laravel\Support\Facades\Response;

class UsersController extends Controller
{
    public function store(UserRequest $request)
    {
        $user = new User([
            'username' => $request->username,
            'password' => $request->password,
            'realname' => $request->realname,
            'status' => $request->status,
            'remark' => $request->remark,
        ]);

        $user->save();

        return Response::success(new UserResource($user));
    }
}
