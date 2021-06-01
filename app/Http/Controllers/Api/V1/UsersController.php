<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\UserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\Rule;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
        return $this->getPermissionUrl();
        $permission = collect($user->getAllPermissions())->first(function ($permission) use ($request) {
            return $request->routeIs(api_route_name($permission));
        });
        return Response::success($permission);
        $userData = $request->only(['username', 'password', 'realname', 'phone', 'platform_id', 'status', 'remark']);
        
        $groupIds = $request->groups;

        $userService->update($user, $userData, $groupIds);

        return Response::success();
    }

    public function getPermissionUrl($parentId = null, $permission = null, $preName = null) {
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
                
                $data[] = $this->getPermissionUrl($rule->id, $permission, $preName);

                return $data;
            });

        $result = [];
        array_walk_recursive($all, function($value) use (&$result) {
        array_push($result, $value);
        });

        return $result;
    }
}
