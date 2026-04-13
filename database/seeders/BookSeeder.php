<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->where('email', 'admin@gestaolivros.com')->firstOrFail();

        $books = [
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'description' => 'Boas práticas para escrita de código limpo e legível.',
                'published_year' => 2008,
            ],
            [
                'title' => 'Refactoring',
                'author' => 'Martin Fowler',
                'description' => 'Técnicas para melhorar código existente sem alterar seu comportamento.',
                'published_year' => 1999,
            ],
            [
                'title' => 'Domain-Driven Design',
                'author' => 'Eric Evans',
                'description' => 'Conceitos de modelagem de domínio aplicada a software.',
                'published_year' => 2003,
            ],
            [
                'title' => 'The Pragmatic Programmer',
                'author' => 'Andrew Hunt',
                'description' => 'Conselhos práticos para desenvolvimento de software profissional.',
                'published_year' => 1999,
            ],
            [
                'title' => 'Design Patterns',
                'author' => 'Erich Gamma, Richard Helm, Ralph Johnson, John Vlissides',
                'description' => 'Catálogo clássico de padrões de projeto orientados a objetos.',
                'published_year' => 1994,
            ],
            [
                'title' => 'Laravel: Up & Running',
                'author' => 'Matt Stauffer',
                'description' => 'Introdução prática ao desenvolvimento com Laravel.',
                'published_year' => 2019,
            ],
            [
                'title' => 'Código Limpo na Prática',
                'author' => 'Autor Exemplo',
                'description' => 'Livro fictício para compor dados iniciais do sistema.',
                'published_year' => 2020,
            ],
            [
                'title' => 'Arquitetura de Software Moderna',
                'author' => 'Autor Exemplo 2',
                'description' => 'Livro fictício sobre arquitetura e boas práticas.',
                'published_year' => 2021,
            ],
        ];

        foreach ($books as $bookData) {
            Book::query()->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'title' => $bookData['title'],
                ],
                [
                    'author' => $bookData['author'],
                    'description' => $bookData['description'],
                    'published_year' => $bookData['published_year'],
                ]
            );
        }
    }
}
