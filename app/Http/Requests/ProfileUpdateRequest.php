<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     * Regras de validação do formulário
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
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
        ];
    }
}
