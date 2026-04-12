<?php

namespace App\Actions;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use DomainException;

class LoanAction
{
    /**
     * Cria um novo empréstimo, respeitando as regras de negócio.
     */
    public function create(User $user, Book $book): Loan
    {
        $this->validateLoanRules($user, $book);

        return Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'loan_date' => now(),
            'due_date' => Carbon::now()->addDays(2),
        ]);
    }

    /**
     * Valida as regras de negócio do empréstimo.
     */
    private function validateLoanRules(User $user, Book $book): void
    {
        if ($this->hasReachedLoanLimit($user)) {
            throw new DomainException('O usuário já possui 3 livros emprestados no momento.');
        }

        if (! $this->isBookAvailable($book)) {
            throw new DomainException('Este livro não está disponível para empréstimo.');
        }
    }

    /**
     * Verifica se o usuário atingiu o limite de empréstimos ativos.
     */
    private function hasReachedLoanLimit(User $user): bool
    {
        return $user->loans()
            ->whereNull('returned_at')
            ->count() >= 3;
    }

    /**
     * Verifica se o livro está disponível para empréstimo.
     */
    private function isBookAvailable(Book $book): bool
    {
        return ! $book->loans()
            ->whereNull('returned_at')
            ->exists();
    }
}