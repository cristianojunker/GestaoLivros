<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    //Mensagens de erro do formulário
    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string'   => 'O nome deve ser um texto válido.',
            'name.max'      => 'O nome deve ter no máximo 255 caracteres.',

            'email.required'   => 'O campo e-mail é obrigatório.',
            'email.string'     => 'O e-mail deve ser um texto válido.',
            'email.lowercase'  => 'O e-mail deve estar em letras minúsculas.',
            'email.email'      => 'Informe um endereço de e-mail válido.',
            'email.max'        => 'O e-mail deve ter no máximo 255 caracteres.',
            'email.unique'     => 'Este e-mail já está em uso.',

            'password.required'   => 'O campo senha é obrigatório.',
            'password.confirmed' => 'A confirmação da senha não confere.',
        ];
    }
}
