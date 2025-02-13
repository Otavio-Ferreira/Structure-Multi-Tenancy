<?php

namespace App\Http\Requests\Api\Authentication;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SendRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            "email" => "required|email",
            "route" => "required|url",
        ];
    }

    public function messages()
    {
        return [
            "email.required" => "É necessário inserir um email.",
            "email.email" => "É necessário inserir um email válido.",
            "route.required" => "É necessário inserir uma rota de redirecionamento.",
            "route.url" => "É necessário inserir uma rota de redirecionamento válida.",
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
