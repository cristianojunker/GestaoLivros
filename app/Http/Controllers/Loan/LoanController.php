<?php

namespace App\Http\Controllers\Loan;

use App\Actions\BookAction;
use App\Actions\LoanAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Loan\LoanRequest;
use App\Models\Loan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use DomainException;
use Throwable;

class LoanController extends Controller
{
    public function __construct(
        private readonly LoanAction $loanAction,
        private readonly BookAction $bookAction
    ) {}

    public function store(LoanRequest $request): RedirectResponse
    {
        try {
            $book = $this->bookAction->findById((int) $request->validated('book_id'));

            $this->loanAction->create(Auth::user(), $book);

            return redirect()
                ->route('books.show', $book->id)
                ->with('success', 'Empréstimo realizado com sucesso.');
        } catch (DomainException $e) {
            return back()->withErrors([
                'error' => $e->getMessage(),
            ]);
        } catch (Throwable $e) {
            report($e);

            return back()->withErrors([
                'error' => 'Ocorreu um erro ao realizar o empréstimo.',
            ]);
        }
    }

    public function registerReturn(string $loan): RedirectResponse
    {
        try {
            $loanModel = Loan::query()
                ->whereNull('returned_at')
                ->findOrFail((int) $loan);

            $this->loanAction->returnLoan($loanModel);

            return redirect()
                ->route('loans.dashboard')
                ->with('success', 'Devolução registrada com sucesso.');
        } catch (DomainException $e) {
            return back()->withErrors([
                'error' => $e->getMessage(),
            ]);
        } catch (Throwable $e) {
            report($e);

            return back()->withErrors([
                'error' => 'Ocorreu um erro ao registrar a devolução.',
            ]);
        }
    }
}