<?php

namespace App\Http\Controllers\Loan;

use App\Actions\LoanAction;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class LoanDashboardController extends Controller
{
    public function __construct(
        private readonly LoanAction $loanAction
    ) {}

    /**
     * Exibe o dashboard com os empréstimos ativos.
     */
    public function index(): View
    {
        $loans = $this->loanAction->listActive();

        return view('loans.dashboard', compact('loans'));
    }
}