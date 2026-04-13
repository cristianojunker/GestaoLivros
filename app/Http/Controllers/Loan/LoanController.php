<?php

namespace App\Http\Controllers\Loan;

use App\Actions\BookAction;
use App\Actions\LoanAction;
use App\Exceptions\BookUnavailableForLoanException;
use App\Exceptions\LoanAlreadyReturnedException;
use App\Exceptions\LoanLimitReachedException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Loan\LoanRequest;
use App\Models\Loan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Throwable;

class LoanController extends Controller
{
    public function __construct(
        private readonly LoanAction $loanAction,
        private readonly BookAction $bookAction
    ) {}

    // Cria um empréstimo após buscar o livro pelo id
    public function store(LoanRequest $request): RedirectResponse
    {
        $book = $this->bookAction->findById((int) $request->validated('book_id'));

        try {
            $this->loanAction->create(Auth::user(), $book);
        } catch (LoanLimitReachedException | BookUnavailableForLoanException $e) {
            return back()->withErrors([
                'error' => $e->getMessage(),
            ]);
        }catch (Throwable $e) {
            report($e);
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao realizar o empréstimo. Por favor, tente novamente.',
            ]);
        } 

        return redirect()
            ->route('books.show', $book->id)
            ->with('success', 'Empréstimo realizado com sucesso.');
    }

    // Registra a devolução de um empréstimo
    public function registerReturn(string $loan): RedirectResponse
    {
        $loanModel = Loan::query()->findOrFail((int) $loan);

        try {
            $this->loanAction->returnLoan($loanModel);
        } catch (LoanAlreadyReturnedException $e) {
            return back()->withErrors([
                'error' => $e->getMessage(),
            ]);
        } catch (Throwable $e) {
            report($e);
            return back()->withErrors([
                'error' => 'Ocorreu um erro ao registrar a devolução. Por favor, tente novamente.',
            ]);
        } 

        return redirect()
            ->route('loans.dashboard')
            ->with('success', 'Devolução registrada com sucesso.');
    }
}