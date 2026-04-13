<?php

namespace Tests\Feature\Books;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_autenticado_consegue_criar_livro(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post(route('books.store'), [
                'title' => 'The Pragmatic Programmer',
                'author' => 'Andrew Hunt',
                'description' => 'Livro clássico de engenharia de software.',
                'published_year' => 1999,
            ]);

        $response->assertRedirect(route('books.index'));

        $this->assertDatabaseHas('books', [
            'user_id' => $user->id,
            'title' => 'The Pragmatic Programmer',
            'author' => 'Andrew Hunt',
            'published_year' => 1999,
        ]);
    }

    public function test_usuario_nao_edita_livro_de_outro_usuario(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $book = Book::factory()->create([
            'user_id' => $owner->id,
            'title' => 'Livro Original',
        ]);

        $response = $this
            ->actingAs($intruder)
            ->put(route('books.update', $book), [
                'title' => 'Livro Alterado Indevidamente',
                'author' => $book->author,
                'description' => $book->description,
                'published_year' => $book->published_year,
            ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Livro Original',
        ]);
    }

    public function test_usuario_nao_deleta_livro_de_outro_usuario(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();

        $book = Book::factory()->create([
            'user_id' => $owner->id,
        ]);

        $response = $this
            ->actingAs($intruder)
            ->delete(route('books.destroy', $book));

        $response->assertForbidden();

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
        ]);
    }
}
