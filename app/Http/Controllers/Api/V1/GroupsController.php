<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\GroupRequest;
use App\Models\Group;
use Illuminate\Http\Request;
use Jiannei\Response\Laravel\Support\Facades\Response;

class GroupsController extends Controller
{
    public function index()
    {
        $groups = Group::with('groupPermission')->paginate(10);

        return Response::success($groups);
    }

    public function store(GroupRequest $request)
    {
        $group = $request->only([
            'title', 'parent_id', 'type', 'remark', 'status',
        ]);

        $ruleIds = $request->rules;

        \DB::transaction(function () use ($group, $ruleIds) {
            $group = new Group($group);
            $group->save();

            if ($ruleIds) {
                $group->permissionAttach($group, $ruleIds);
            }
        });

        return Response::success();
    }

    public function update(GroupRequest $request, Group $group)
    {
        $groupData = $request->only([
            'title', 'parent_id', 'type', 'remark', 'status',
        ]);
        
        $ruleIds = $request->rules;

        \DB::transaction(function () use ($group, $ruleIds, $groupData) {
            $group->update($groupData);
            $group->save();
            
            // 删除用户组之前的权限
            $group->permissionDetach($group, collect($group->groupPermission)->pluck('id')->all());

            // 重新生成用户组权限
            if ($ruleIds) {
                $group->permissionAttach($group, $ruleIds);
            }
        });

        return Response::success();
    }
}
