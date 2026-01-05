<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email|min:6|max:50',
            'password' => 'required|min:6'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'O campo nome é obrigatório!',
            'name.min' => 'O campo nome deve conter no minimo :min letras',
            'name.max' => 'O campo nome de conter no maximo :max letras',
            'email.required' => 'O campo email é obrigatório!',
            'email.email' => 'Informe um email válido!',
            'email.unique' => 'Este email já está em uso!',
            'email.min' => 'O campo email deve conter no minimo :min letras!',
            'password.required' => 'O campo senha é obrigatório!',
            'password.min' => 'A senha deve conter no minimo 6 letras!',
        ];
    }
}
