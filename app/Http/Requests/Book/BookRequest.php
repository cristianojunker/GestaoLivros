<?php

namespace App\Http\Requests\Book;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'published_year' => ['nullable', 'integer', 'min:1000', 'max:' . date('Y')],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório.',
            'author.required' => 'O autor é obrigatório.',
            'published_year.integer' => 'O ano de publicação deve ser um número inteiro.',
            'published_year.min' => 'O ano de publicação informado é inválido.',
            'published_year.max' => 'O ano de publicação não pode ser maior que o ano atual.',
        ];
    }
}