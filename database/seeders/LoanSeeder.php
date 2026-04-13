<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->where('email', 'admin@gestaolivros.com')->firstOrFail();

        $cleanCode = Book::query()->where('title', 'Clean Code')->firstOrFail();
        $refactoring = Book::query()->where('title', 'Refactoring')->firstOrFail();
        $ddd = Book::query()->where('title', 'Domain-Driven Design')->firstOrFail();

        Loan::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'book_id' => $cleanCode->id,
                'returned_at' => null,
            ],
            [
                'loan_date' => now()->subDay(),
                'due_date' => now()->addDay(),
                'due_soon_notified_at' => null,
            ]
        );

        Loan::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'book_id' => $refactoring->id,
                'returned_at' => null,
            ],
            [
                'loan_date' => now()->subDay(),
                'due_date' => now()->addHours(6),
                'due_soon_notified_at' => null,
            ]
        );

        Loan::query()->updateOrCreate(
            [
                'user_id' => $user->id,
                'book_id' => $ddd->id,
                'returned_at' => now()->subHours(2),
            ],
            [
                'loan_date' => now()->subDays(3),
                'due_date' => now()->subDay(),
                'due_soon_notified_at' => null,
            ]
        );
    }
}
