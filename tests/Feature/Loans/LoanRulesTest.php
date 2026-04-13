<?php

namespace Tests\Feature\Loans;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanRulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_nao_pode_pegar_mais_de_tres_livros(): void
    {
        $user = User::factory()->create();

        $booksAlreadyBorrowed = Book::factory()->count(3)->create();

        foreach ($booksAlreadyBorrowed as $book) {
            Loan::factory()->create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'returned_at' => null,
            ]);
        }

        $availableBook = Book::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from(route('books.show', $availableBook))
            ->post(route('loans.store'), [
                'book_id' => $availableBook->id,
            ]);

        $response->assertRedirect(route('books.show', $availableBook));
        $response->assertSessionHasErrors([
            'error' => 'O usuário já possui 3 livros emprestados no momento.',
        ]);

        $this->assertDatabaseMissing('loans', [
            'user_id' => $user->id,
            'book_id' => $availableBook->id,
        ]);
    }

    public function test_livro_emprestado_nao_pode_ser_emprestado_novamente(): void
    {
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();
        $book = Book::factory()->create();

        Loan::factory()->create([
            'user_id' => $firstUser->id,
            'book_id' => $book->id,
            'returned_at' => null,
        ]);

        $response = $this
            ->actingAs($secondUser)
            ->from(route('books.show', $book))
            ->post(route('loans.store'), [
                'book_id' => $book->id,
            ]);

        $response->assertRedirect(route('books.show', $book));
        $response->assertSessionHasErrors([
            'error' => 'Este livro não está disponível para empréstimo.',
        ]);

        $this->assertEquals(
            1,
            Loan::query()->where('book_id', $book->id)->whereNull('returned_at')->count()
        );
    }

    public function test_devolucao_libera_o_livro_para_novo_emprestimo(): void
    {
        $firstUser = User::factory()->create();
        $secondUser = User::factory()->create();
        $book = Book::factory()->create();

        $loan = Loan::factory()->create([
            'user_id' => $firstUser->id,
            'book_id' => $book->id,
            'returned_at' => null,
        ]);

        $returnResponse = $this
            ->actingAs($firstUser)
            ->patch(route('loans.return', $loan));

        $returnResponse->assertRedirect(route('loans.dashboard'));
        $this->assertNotNull($loan->fresh()->returned_at);

        $newLoanResponse = $this
            ->actingAs($secondUser)
            ->post(route('loans.store'), [
                'book_id' => $book->id,
            ]);

        $newLoanResponse->assertRedirect(route('books.show', $book));

        $this->assertEquals(
            1,
            Loan::query()->where('book_id', $book->id)->whereNull('returned_at')->count()
        );
    }
}
