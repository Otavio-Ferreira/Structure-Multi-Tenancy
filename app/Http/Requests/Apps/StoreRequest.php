<?php

namespace App\Http\Requests\Apps;

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
            'name' => 'required|string',
            'controller' => 'required|string',
            'color' => 'required|string',
            'status' => 'required|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            "name.required" => "É necessário inserir uma nome.",
            "name.string" => "É necessário inserir um nome em texto.",
            "controlle.required" => "É necessário inserir uma nome do controller.",
            "controlle.string" => "É necessário inserir um nome do controller em texto.",
            "color.required" => "É necessário inserir uma cor.",
            "color.string" => "É necessário inserir uma cor.",
            "status.required" => "É necessário inserir uma status.",
            "status.in" => "É necessário inserir um status válido.",
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
