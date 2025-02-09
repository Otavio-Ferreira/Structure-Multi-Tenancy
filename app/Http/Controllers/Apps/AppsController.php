<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apps\StoreRequest;
use App\Http\Requests\Apps\UpdateRequest;
use App\Services\Apps\AppService;

class AppsController extends Controller
{
    protected $appService;

    public function __construct(
        AppService $appService
    )
    {
        $this->appService = $appService;
    }

    public function getApp($id) {
        return $this->appService->getAppResponse($id);
    }
    public function getApps() {
        return $this->appService->getAllAppsResponse();
    }
    public function setApp(StoreRequest $request) {
        return $this->appService->setAppResponse($request);
    }
    public function updateApp(UpdateRequest $request, $id) {
        return $this->appService->updateAppResponse($request, $id);
    }
    public function setAppsToTenant() {}
}
