<?php

namespace App\Console\Commands;

use App\Actions\LoanAction;
use App\Mail\LoanDueSoonMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckLoanDueSoonCommand extends Command
{
    protected $signature = 'loans:check-due-soon';

    protected $description = 'Verifica empréstimos com 12 horas ou menos para vencer e envia alertas por e-mail';

    public function __construct(
        private readonly LoanAction $loanAction
    ) {
        parent::__construct();
    }

    /**
     * Executa a verificação dos empréstimos próximos do vencimento.
     */
    public function handle(): int
    {
        $loans = $this->loanAction->listDueSoonForNotification();

        if ($loans->isEmpty()) {
            $this->info('Nenhum empréstimo próximo do vencimento foi encontrado.');

            return self::SUCCESS;
        }

        foreach ($loans as $loan) {
            Mail::to($loan->user->email)->queue(new LoanDueSoonMail($loan));

            $this->loanAction->markDueSoonNotificationAsSent($loan);
        }

        $this->info("{$loans->count()} alerta(s) enviado(s) com sucesso.");

        return self::SUCCESS;
    }
}