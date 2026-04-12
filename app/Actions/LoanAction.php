<?php

namespace App\Actions;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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
     * Lista os empréstimos ativos com usuário e livro.
     */
    public function listActive(): LengthAwarePaginator
    {
        return Loan::query()
            ->with(['user', 'book'])
            ->whereNull('returned_at')
            ->orderBy('due_date')
            ->paginate(10);
    }

    /**
     * Registra a devolução de um empréstimo.
     */
    public function returnLoan(Loan $loan): Loan
    {
        if (! is_null($loan->returned_at)) {
            throw new DomainException('Este empréstimo já foi devolvido.');
        }

        $loan->update([
            'returned_at' => now(),
        ]);

        return $loan->refresh();
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

    /**
     * Busca empréstimos ativos com 12 horas ou menos para vencer e ainda não notificados.
     */
    public function listDueSoonForNotification()
    {
        return Loan::query()
            ->with(['user', 'book'])
            ->whereNull('returned_at')
            ->whereNull('due_soon_notified_at')
            ->where('due_date', '>', now())
            ->where('due_date', '<=', now()->addHours(12))
            ->get();
    }

    /**
     * Marca que o alerta de vencimento já foi enviado.
     */
    public function markDueSoonNotificationAsSent(Loan $loan): Loan
    {
        $loan->update([
            'due_soon_notified_at' => now(),
        ]);

        return $loan->refresh();
    }
}