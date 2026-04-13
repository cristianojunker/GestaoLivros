<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // Implementação das regras de validação
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ];
    }

    //Mensagens de erro do formulário
    public function messages(): array
    {
        return [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.string'   => 'O e-mail deve ser um texto válido.',
            'email.email'    => 'Informe um endereço de e-mail válido.',

            'password.required' => 'O campo senha é obrigatório.',
            'password.string'   => 'A senha deve ser um texto válido.',

            'remember.boolean' => 'O campo lembrar deve ser verdadeiro ou falso.',
        ];
    }

    // Converssão para valor booleano do campo remember
    public function remember(): bool
    {
        return $this->boolean('remember');
    }

    // Chave de throttling. Usada pela classe RateLimiter para controle de tentativas
    public function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->string('email')).'|'.$this->ip()
        );
    }
}