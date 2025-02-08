<?php

namespace App\Http\Requests\Users;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|exists:roles,name',
            "route" => "required|url",
        ];
    }

    public function messages()
    {
        return [
            "email.required" => "É necessário inserir um email.",
            "email.email" => "É necessário inserir um email válido.",
            "name.required" => "É necessário inserir uma nome.",
            "name.string" => "É necessário inserir um nome em texto.",
            "role.required" => "É necessário inserir um grupo de permissão.",
            "role.string" => "É necessário inserir um grupo de permissão válido.",
            'role.exists' => 'O grupo selecionada não é válido.',
            "route.required" => "É necessário inserir uma rota de envio.",
            "route.url" => "É necessário inserir uma rota válida.",
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'validate' => false,
            'message' => 'Erro de validação.',
            'errors' => $validator->errors()
        ], 422));
    }
}
