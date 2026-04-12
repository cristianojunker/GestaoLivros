<?php

namespace App\Http\Controllers\Book;

use App\Actions\BookAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Book\BookRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Throwable;

class BookController extends Controller
{
    public function __construct(
        private readonly BookAction $bookAction
    ) {}

    public function index(): View
    {
        $books = $this->bookAction->listByUser(Auth::user());

        return view('books.index', compact('books'));
    }

    public function create(): View
    {
        return view('books.create');
    }

    public function store(BookRequest $request): RedirectResponse
    {
        try {
            $this->bookAction->create(Auth::user(), $request->validated());

            return redirect()
                ->route('books.index')
                ->with('success', 'Livro cadastrado com sucesso.');
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'error' => 'Ocorreu um erro ao cadastrar o livro.',
                ]);
        }
    }

    public function show(int $book): View
    {
        $book = $this->bookAction->findByUser(Auth::user(), $book);

        return view('books.show', compact('book'));
    }

    public function edit(int $book): View
    {
        $book = $this->bookAction->findByUser(Auth::user(), $book);

        return view('books.edit', compact('book'));
    }

    public function update(BookRequest $request, int $book): RedirectResponse
    {
        try {
            $bookModel = $this->bookAction->findByUser(Auth::user(), $book);
            $this->bookAction->update($bookModel, $request->validated());

            return redirect()
                ->route('books.index')
                ->with('success', 'Livro atualizado com sucesso.');
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'error' => 'Ocorreu um erro ao atualizar o livro.',
                ]);
        }
    }

    public function destroy(int $book): RedirectResponse
    {
        try {
            $bookModel = $this->bookAction->findByUser(Auth::user(), $book);
            $this->bookAction->delete($bookModel);

            return redirect()
                ->route('books.index')
                ->with('success', 'Livro removido com sucesso.');
        } catch (Throwable $e) {
            report($e);

            return back()->withErrors([
                'error' => 'Ocorreu um erro ao remover o livro.',
            ]);
        }
    }
}