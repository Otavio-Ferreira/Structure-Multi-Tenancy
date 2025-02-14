<?php

namespace App\Http\Controllers\Api\Tenants\Apps;

use App\Http\Controllers\Controller;
use App\Models\Apps\Apps;
use App\Models\Tenants\TenantApps;
use App\Services\Apps\AppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Tymon\JWTAuth\Facades\JWTAuth;

class AppsController extends Controller
{
    protected $appService;

    public function __construct(
        AppService $appService
    )
    {
        $this->appService = $appService;    
    }
    public function getApp($id){

    }

    public function getApps(){
        return $this->appService->getAllTennatsAppsResponse();
    }
}
