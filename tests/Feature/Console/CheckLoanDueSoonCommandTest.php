<?php

namespace Tests\Feature\Console;

use App\Mail\LoanDueSoonMail;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckLoanDueSoonCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_comando_de_alerta_encontra_apenas_emprestimos_com_ate_doze_horas(): void
    {
        Mail::fake();

        $user = User::factory()->create();

        $loanWithinSixHours = Loan::factory()->create([
            'user_id' => $user->id,
            'due_date' => now()->addHours(6),
            'returned_at' => null,
            'due_soon_notified_at' => null,
        ]);

        $loanWithinTwelveHours = Loan::factory()->create([
            'user_id' => $user->id,
            'due_date' => now()->addHours(12),
            'returned_at' => null,
            'due_soon_notified_at' => null,
        ]);

        $loanAfterThirteenHours = Loan::factory()->create([
            'user_id' => $user->id,
            'due_date' => now()->addHours(13),
            'returned_at' => null,
            'due_soon_notified_at' => null,
        ]);

        $overdueLoan = Loan::factory()->create([
            'user_id' => $user->id,
            'due_date' => now()->subHour(),
            'returned_at' => null,
            'due_soon_notified_at' => null,
        ]);

        $returnedLoan = Loan::factory()->create([
            'user_id' => $user->id,
            'due_date' => now()->addHours(4),
            'returned_at' => now(),
            'due_soon_notified_at' => null,
        ]);

        $alreadyNotifiedLoan = Loan::factory()->create([
            'user_id' => $user->id,
            'due_date' => now()->addHours(5),
            'returned_at' => null,
            'due_soon_notified_at' => now(),
        ]);

        $this->artisan('loans:check-due-soon')
            ->expectsOutput('2 alerta(s) enviado(s) com sucesso.')
            ->assertSuccessful();

        Mail::assertQueued(LoanDueSoonMail::class, 2);

        $this->assertNotNull($loanWithinSixHours->fresh()->due_soon_notified_at);
        $this->assertNotNull($loanWithinTwelveHours->fresh()->due_soon_notified_at);

        $this->assertNull($loanAfterThirteenHours->fresh()->due_soon_notified_at);
        $this->assertNull($overdueLoan->fresh()->due_soon_notified_at);
        $this->assertNull($returnedLoan->fresh()->due_soon_notified_at);

        $this->assertNotNull($alreadyNotifiedLoan->fresh()->due_soon_notified_at);
    }
}
