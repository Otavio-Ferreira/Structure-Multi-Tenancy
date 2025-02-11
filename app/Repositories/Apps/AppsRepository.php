<?php

namespace App\Repositories\Apps;

use App\Http\Requests\Apps\StoreRequest;
use App\Http\Requests\Apps\UpdateRequest;
use App\Models\Apps\Apps;
use Illuminate\Database\Eloquent\Collection;

interface AppsRepository
{
    public function getApp($id) :Apps;

    public function getAllApps() :Collection;

    public function setApp(StoreRequest $request) :Apps;

    public function updateApp(UpdateRequest $request, $id) :Apps;
    
}
