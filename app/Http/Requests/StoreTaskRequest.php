<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name' => 'required|min:4|string',
            'description' => 'required|min:8|string',
            'priority' => 'required',
            'status' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo nome é obrigatório!',
            'name.min' => 'O campo nome é deve conter no minimo :min caracteres!',
            'description.required' => 'O campo descrição é obrigatório!',
            'description.min' => 'O campo descrição deve conter no minimo :min caracteres!',
            'priority.required' => 'O campo prioridade é obrigatório!',
            'status.required' => 'O campo status é obrigatório!',
        ];
    }
}
