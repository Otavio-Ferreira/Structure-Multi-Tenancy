<?php

namespace App\Http\Requests\TenantsApps;

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
            'apps' => 'array',
            'tenant_id' => 'required|uuid',
        ];
    }

    public function messages()
    {
        return [
            // "apps.required" => "É necessário inserir apps.",
            "apps.array" => "É necessário inserir apps válidos.",
            "tenant_id.required" => "É necessário inserir um tenant.",
            "tenant_id.uuid" => "É necessário inserir um tenant válido.",
        ];
    }
}
