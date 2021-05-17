<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\PlatformRequest;
use App\Http\Resources\PlatformCollection;
use App\Http\Resources\PlatformResource;
use App\Models\Platform;
use Illuminate\Http\Request;
use Jiannei\Response\Laravel\Support\Facades\Response;

class PlatformsController extends Controller
{
    public function index()
    {
        return Response::success(new PlatformCollection(Platform::paginate(10)));
    }

    public function store(PlatformRequest $request)
    {
        $platform = new Platform([
            'title' => $request->title,
            'name' => $request->name,
            'status' => $request->status,
        ]);

        $platform->save();

        return Response::success(new PlatformResource($platform));
    }

    public function udpate(PlatformRequest $request, Platform $platform)
    {
        $platform->update($request->all());

        return Response::success(new PlatformResource($platform));
    }

    public function destroy(Platform $platform)
    {
        $platform->delete();

        return Response::success();
    }
}
