<?php

namespace Stylemix\Settings;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class SettingsController extends Controller
{

    public function store(Request $request)
    {
        app(SettingsManager::class)->set($request->input());

        return Response::create();
    }


    public function get()
    {
        $data = app(SettingsManager::class)->all();

        return JsonResponse::create($data);
    }

}