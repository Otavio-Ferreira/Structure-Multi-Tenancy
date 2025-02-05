<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Http\Requests\Apps\StoreRequest;
use App\Models\Apps\Apps;
use Illuminate\Http\Request;

class AppsController extends Controller
{
    public function store(StoreRequest $request){
        Apps::create([
            "name" => $request->name
        ]);

        return redirect()->back()->with("toast_success", "App cadastrado com sucesso!");
    }
}
