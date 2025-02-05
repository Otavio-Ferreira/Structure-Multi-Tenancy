<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Apps\Apps;
use App\Models\Tenants\Tenant;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    private $data = [];
    
    public function index(){
        $this->data['apps'] = Apps::all();
        $this->data['roles'] = Role::all();
        $this->data['tenants'] = Tenant::with("apps")->get();

        return view('pages.home.index', $this->data);
    }
}
