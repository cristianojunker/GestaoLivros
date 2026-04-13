<?php

namespace Tests\Feature\Books;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookBrowsingTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitante_consegue_ver_lista_de_livros(): void
    {
        Book::factory()->create([
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
        ]);

        Book::factory()->create([
            'title' => 'Refactoring',
            'author' => 'Martin Fowler',
        ]);

        $response = $this->get(route('books.index'));

        $response->assertOk();
        $response->assertSee('Clean Code');
        $response->assertSee('Refactoring');
    }

    public function test_visitante_consegue_ver_detalhe_do_livro(): void
    {
        $book = Book::factory()->create([
            'title' => 'Domain-Driven Design',
            'author' => 'Eric Evans',
            'description' => 'Livro sobre modelagem de domínio.',
        ]);

        $response = $this->get(route('books.show', $book));

        $response->assertOk();
        $response->assertSee('Domain-Driven Design');
        $response->assertSee('Eric Evans');
        $response->assertSee('Livro sobre modelagem de domínio.');
    }
}
