<?php

namespace App\Http\Controllers\Book;

use App\Actions\BookAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Book\BookRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class BookController extends Controller
{
    public function __construct(
        private readonly BookAction $bookAction
    ) {}

    public function index(Request $request): View
    {
        $search = $request->string('search')->toString();
        $books = $this->bookAction->list($search);

        return view('books.index', compact('books', 'search'));
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
        $book = $this->bookAction->findById($book);

        return view('books.show', compact('book'));
    }

    public function edit(int $book): View
    {
        $book = $this->bookAction->findById($book);

        $this->authorize('update', $book);

        return view('books.edit', compact('book'));
    }

    public function update(BookRequest $request, int $book): RedirectResponse
    {
        try {
            $bookModel = $this->bookAction->findById($book);

            $this->authorize('update', $bookModel);

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
            $bookModel = $this->bookAction->findById($book);

            $this->authorize('delete', $bookModel);

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