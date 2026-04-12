<?php

namespace App\Actions;

use App\Models\Book;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BookAction
{
    /**
     * Lista os livros com busca por título e paginação.
     */
    public function list(string|null $search = null): LengthAwarePaginator
    {
        return Book::query()
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', '%' . trim($search) . '%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }

    /**
     * Cria um novo livro vinculado ao usuário autenticado.
     */
    public function create(User $user, array $data): Book
    {
        $data['user_id'] = $user->id;

        return Book::create($data);
    }

    /**
     * Busca um livro pelo id.
     */
    public function findById(int $bookId): Book
    {
        return Book::query()->findOrFail($bookId);
    }

    /**
     * Atualiza um livro.
     */
    public function update(Book $book, array $data): Book
    {
        $book->update($data);

        return $book;
    }

    /**
     * Remove um livro.
     */
    public function delete(Book $book): void
    {
        $book->delete();
    }
}