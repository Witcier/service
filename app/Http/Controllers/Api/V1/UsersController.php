<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\UserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Jiannei\Response\Laravel\Support\Facades\Response;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::paginate(6);

        return Response::success(new UserCollection($users));
    }

    public function store(UserRequest $request, UserService $userService)
    {
        $user = $request->only(['username', 'password', 'realname', 'phone', 'platform_id', 'status', 'remark']);

        $groupIds = $request->groups;

        $userService->store($user, $groupIds);

        return Response::success();
    }

    public function update(UserRequest $request, User $user, UserService $userService)
    {
        $userData = $request->only(['username', 'password', 'realname', 'phone', 'platform_id', 'status', 'remark']);
        
        $groupIds = $request->groups;

        $userService->update($user, $userData, $groupIds);

        return Response::success();
    }
}
