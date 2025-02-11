<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apps\StoreRequest;
use App\Http\Requests\Apps\UpdateRequest;
use App\Models\Apps\Apps;
use App\Services\Apps\AppService;
use Illuminate\Http\Request;

class AppsController extends Controller
{
    protected $data = [];
    protected $appService;

    public function __construct(
        AppService $appService
    ) {
        $this->appService = $appService;
    }

    public function index()
    {
        $this->data['apps'] = Apps::all();
        return view('pages.apps.index' , $this->data);
    }
    public function getApp($id)
    {
        return $this->appService->getAppResponse($id);
    }
    public function getApps()
    {
        return $this->appService->getAllAppsResponse();
    }
    public function setApp(StoreRequest $request)
    {
        return $this->appService->setAppResponse($request);
    }
    public function updateApp(UpdateRequest $request, $id)
    {
        return $this->appService->updateAppResponse($request, $id);
    }
    public function setAppsToTenant() {}
}
