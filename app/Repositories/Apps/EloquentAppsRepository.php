<?php

namespace App\Repositories\Apps;

use App\Http\Requests\Apps\StoreRequest;
use App\Http\Requests\Apps\UpdateRequest;
use App\Models\Apps\Apps;
use Illuminate\Database\Eloquent\Collection;

class EloquentAppsRepository implements AppsRepository
{
    public function getApp($id) :Apps {
        return Apps::find($id);
    }

    public function getAllApps() :Collection {
        return Apps::all();
    }

    public function setApp(StoreRequest $request) :Apps {
        $app = Apps::create([
            "name" => $request->name,
            "controller" => $request->controller,
            "color" => $request->color,
            "status" => $request->status
        ]);

        return $app;
    }
    public function updateApp(UpdateRequest $request, $id) :Apps {
        $app = Apps::find($id);

        $app->name = $request->name;
        $app->controller = $request->controller;
        $app->color = $request->color;
        $app->status = $request->status;

        $app->save();

        return $app;
    }
}
