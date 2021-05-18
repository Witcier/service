<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\RuleRequest;
use App\Http\Resources\Rule\RuleCollection;
use App\Http\Resources\Rule\RuleResource;
use App\Models\Rule;
use Illuminate\Http\Request;
use Jiannei\Response\Laravel\Support\Facades\Response;

class RulesController extends Controller
{
    public function index()
    {
        $rules = Rule::query()
            ->where('level', Rule::TYPE_MODULE)
            ->with(['children', 'children.children'])
            ->get();

        return Response::success(new RuleCollection($rules));
    }

    public function store(RuleRequest $request)
    {
        $rule = new Rule($request->all());
        $rule->save();

        return Response::success();
    }

    public function show(Rule $rule)
    {
        return Response::success(new RuleResource($rule));
    }

    public function update(RuleRequest $request, Rule $rule)
    {
        $rule->update($request->all());

        return Response::success();
    }

    public function destroy(Rule $rule)
    {
        $rule->delete();

        return Response::success();
    }

    public function getRuleTree($parentId = null, $allRules = null)
    {
        if (is_null($allRules)) {
            $allRules = Rule::all();
        }

        return $allRules
            ->where('parent_id', $parentId)
            ->map(function (Rule $rule) use ($allRules) {
                $data = [
                    'id' => $rule->id,
                    'title' => $rule->title,
                    'name' => $rule->name,
                    'status' => $rule->status,
                ];

                if (!$rule->level === Rule::TYPE_OPERATION) {
                    return $data;
                }

                $data['children'] = $this->getRuleTree($rule->id, $allRules);

                return $data;
            });
    }
}
