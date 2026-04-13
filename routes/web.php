<?php

use App\Http\Controllers\Book\BookController;
use App\Http\Controllers\Loan\LoanController;
use App\Http\Controllers\Loan\LoanDashboardController;
use App\Http\Controllers\ProfileController;
use App\Models\Loan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('auth')->group(function () {
    //Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Empréstimos
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');

    Route::get('/loans/dashboard', [LoanDashboardController::class, 'index'])
        ->name('loans.dashboard');

    Route::patch('/loans/{loan}/return', [LoanController::class, 'registerReturn'])
        ->name('loans.return');
});

//Livros
Route::get('/', function () {
    return redirect()->route('books.index');
});
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/create', [BookController::class, 'create'])->middleware('auth')->name('books.create');
Route::post('/books', [BookController::class, 'store'])->middleware('auth')->name('books.store');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
Route::get('/books/{book}/edit', [BookController::class, 'edit'])->middleware('auth')->name('books.edit');
Route::put('/books/{book}', [BookController::class, 'update'])->middleware('auth')->name('books.update');
Route::delete('/books/{book}', [BookController::class, 'destroy'])->middleware('auth')->name('books.destroy');


require __DIR__.'/auth.php';
