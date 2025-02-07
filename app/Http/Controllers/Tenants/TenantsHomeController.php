<?php

namespace App\Http\Controllers\Tenants;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TenantsHomeController extends Controller
{
    private $data = [];
    
    public function index(){
        $this->data['users'] = User::all();

        return view('tenants.pages.home.index', $this->data);
    }
}
