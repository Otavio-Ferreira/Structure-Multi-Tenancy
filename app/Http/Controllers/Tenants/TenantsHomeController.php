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
        // $this->data['apps'] = Http::withHeaders([
        //     'Accept' => 'application/json',
        //     'tenants_id' => tenant()->id
        // ])->get('http://localhost:8000/api/getApps');

        // dd($this->data['apps']);
        $this->data['users'] = User::all();

        return view('tenants.pages.home.index', $this->data);
    }
}
