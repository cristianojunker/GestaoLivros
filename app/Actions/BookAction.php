<?php

namespace App\Actions;

use App\Models\Book;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BookAction
{
    /**
     * Lista os livros do usuário autenticado com paginação.
     */
    public function listByUser(User $user): LengthAwarePaginator
    {
        return Book::query()
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);
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
     * Busca um livro do usuário autenticado.
     */
    public function findByUser(User $user, int $bookId): Book
    {
        return Book::query()
            ->where('user_id', $user->id)
            ->findOrFail($bookId);
    }

    /**
     * Atualiza um livro do usuário autenticado.
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