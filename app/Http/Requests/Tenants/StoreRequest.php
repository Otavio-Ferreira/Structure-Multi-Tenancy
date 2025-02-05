<?php

namespace App\Http\Requests\Tenants;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'domain' => 'required|string',
            'email' => 'required|email',
            'tenancy_db_name' => 'required|string',
            'tenancy_db_host' => 'required|string',
            'tenancy_db_user' => 'required|string',
            'tenancy_db_password' => 'required|string',
            'tenancy_db_port' => 'required|integer',
            'role' => 'required|exists:roles,name',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O campo nome deve ser um texto.',
            'domain.required' => 'O campo domínio é obrigatório.',
            'domain.string' => 'O campo domínio deve ser um texto.',
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.exists' => 'O e-mail informado não está cadastrado na tabela de usuários.',
            'tenancy_db_name.required' => 'O campo nome do banco de dados é obrigatório.',
            'tenancy_db_name.string' => 'O campo nome do banco de dados deve ser um texto.',
            'tenancy_db_host.required' => 'O campo host do banco de dados é obrigatório.',
            'tenancy_db_host.string' => 'O campo host do banco de dados deve ser um texto.',
            'tenancy_db_user.required' => 'O campo usuário do banco de dados é obrigatório.',
            'tenancy_db_user.string' => 'O campo usuário do banco de dados deve ser um texto.',
            'tenancy_db_password.required' => 'O campo senha do banco de dados é obrigatório.',
            'tenancy_db_password.string' => 'O campo senha do banco de dados deve ser um texto.',
            'tenancy_db_port.required' => 'O campo porta do banco de dados é obrigatório.',
            'tenancy_db_port.integer' => 'O campo porta do banco de dados deve ser um número inteiro.',
            'role.required' => 'O campo papel é obrigatório.',
            'role.in' => 'O papel selecionado é inválido.',
        ];
    }
}
