<?php

namespace App\Http\Controllers\Authentication;

use App\Events\Autenticator\TokenCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\ResetRequest;
use App\Http\Requests\Authentication\SendRequest;
use App\Models\Authentication\Tokens;
use App\Models\User;
use App\Repositories\Authentication\LoginRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginTenantController extends Controller
{
    public $repository;

    public function __construct(LoginRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return view('tenants.pages.authentication.index');
    }

    public function store(Request $request)
    {

        $credentials = $request->only(['email', 'password']);

        if (Auth::attempt($credentials)) {

            if (Auth::user()->status == 1) {
                return redirect()->to('http://' . tenant()->domain->domain . ':8000/dashboard');
            } else {
                Auth::logout();
                return back()->with("toast_error", "verifique se o email e senha foram digitados corretamente.")->withInput();
            }
        }

        return back()->with("toast_error", "verifique se o email e senha foram digitados corretamente.")->withInput();
    }

    public function reset()
    {
        return view('tenants.pages.authentication.reset');
    }

    public function edit(Tokens $token)
    {
        if (isset($token) && $token->status == 1) {
            return view('tenants.pages.authentication.edit')->with('token_id', $token->id);
        }

        return redirect()->to(tenant_route_url('login'));
    }

    public function send(SendRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            try {

                $data = $this->repository->createToken($user, "reset_password");
                $domain = tenant() ? tenant_route_url('login/editar/' .$data['token']) : route('login.edit');
                TokenCreated::dispatch(
                    $data['name'],
                    $data['email'],
                    $data['time'],
                    $data['token'],
                    $data['title'],
                    $domain,
                );

                return redirect()->back()->with("toast_success", "Verifique a caixa de entrada do seu email.");
            } catch (\Throwable $th) {
                return redirect()->back()->with("toast_error", "Erro ao enviar o email, tente novamente em alguns instantes.")->withInput();
            }
        }

        return redirect()->back()->with("toast_warning", "Erro ao enviar o email. Verifique se o email digitado está correto.")->withInput();
    }

    public function update(ResetRequest $request, Tokens $token)
    {
        if (isset($token) && $token->status == 1) {
            try {
                $this->repository->changePassword($request, $token);

                return redirect()->to(tenant_route_url('login'))->with("toast_success", "Senha atualizada, tente fazer login.");
            } catch (\Throwable $th) {
                return redirect()->back()->with("toast_error", "Erro, tente novamente em alguns instantes.")->withInput();
            }
        }

        return redirect()->back()->with("toast_error", "Error ao tentar alterar senha, tente novamente em alguns instantes.")->withInput();
    }

    public function register(Tokens $token)
    {
        if (isset($token) && $token->status == 1) {
            return view('tenants.pages.authentication.register')->with('token_id', $token->id);
        }

        return redirect()->to(tenant_route_url('login'));
    }
}
