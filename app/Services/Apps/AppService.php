<?php

namespace App\Services\Apps;

use App\Http\Requests\Apps\StoreRequest;
use App\Http\Requests\Apps\UpdateRequest;
use App\Repositories\Apps\AppsRepository;

class AppService{

    protected $appsRepository;

    public function __construct(
        AppsRepository $appsRepository
    )
    {
        $this->appsRepository = $appsRepository;
    }
    public function getAppResponse($id)
    {
        try {

            $app = $this->appsRepository->getApp($id);
            if (!$app) {
                return response()->json([
                    "validate" => false,
                    "message" => "Esse app não existe."
                ], 204);
            }

            return response()->json([
                "validate" => true,
                "message" => "Esse app existe.",
                "app" => $app
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "validate" => false,
                "message" => "Erro no servidor."
            ], 500);
        }
    }

    public function getAllAppsResponse()
    {
        try {
            $apps = $this->appsRepository->getAllApps();

            return response()->json([
                "validate" => true,
                "message" => "Busca bem-sucedida.",
                "apps" => $apps
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "validate" => false,
                "message" => "Erro no servidor."
            ], 500);
        }
    }

    public function setAppResponse(StoreRequest $request)
    {
        try {
            $app = $this->appsRepository->setApp($request);
            return redirect()->back()->with('toast_success', 'App adicionado com sucesso.');
            return response()->json([
                "validate" => true,
                "message" => "App criado com sucesso.",
                "app" => $app
            ], 201);
        } catch (\Throwable $th) {
            return redirect()->back()->with('toast_error', 'Erro ao adicionar app.');

            // return response()->json([
            //     "validate" => false,
            //     "message" => "Erro no servidor."
            // ], 500);
        }
    }

    public function updateAppResponse(UpdateRequest $request, $id)
    {
        try {
            $app = $this->appsRepository->updateApp($request, $id);

            $app->name = $request->name;
            $app->status = $request->status;

            $app->save();

            if ($request->role) {
                $app->syncRoles([$request->role]);
            }

            return redirect()->back()->with('toast_success', 'App atualizado com sucesso.');

            // return response()->json([
            //     "validate" => true,
            //     "message" => "App atualizado com sucesso.",
            //     "app" => $app
            // ], 200);
        } catch (\Throwable $th) {
            // return response()->json([
            //     "validate" => false,
            //     "message" => "Erro no servidor."
            // ], 500);
            return redirect()->back()->with('toast_error', 'Erro ao atualizar app.');
        }
    }

}