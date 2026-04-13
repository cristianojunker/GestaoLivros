<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    public function definition(): array
    {
        $loanDate = now()->subDay();
        $dueDate = (clone $loanDate)->addDays(2);

        return [
            'user_id' => User::factory(),
            'book_id' => Book::factory(),
            'loan_date' => $loanDate,
            'due_date' => $dueDate,
            'returned_at' => null,
            'due_soon_notified_at' => null,
        ];
    }

    public function returned(): static
    {
        return $this->state(fn (array $attributes) => [
            'returned_at' => now(),
        ]);
    }
}
