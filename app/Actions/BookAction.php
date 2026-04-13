<?php

namespace App\Actions;

use App\Models\Book;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class BookAction
{
    /**
     * Lista os livros com busca por título e paginação.
     */
    public function list(?string $search = null, bool $onlyAvailable = false): LengthAwarePaginator
    {
        return Book::query()
            ->withActiveLoansCount()
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', '%' . trim($search) . '%');
            })
            ->when($onlyAvailable, function ($query) {
                $query->available();
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

        if (($data['cover_image'] ?? null) instanceof UploadedFile) {
            $data['cover_image'] = $data['cover_image']->store('book-covers', 'public');
        }

        return Book::create($data);
    }

    /**
     * Busca um livro pelo id e tambem o número de empréstimos ativos.
     */
    public function findById(int $bookId): Book
    {
        return Book::query()
            ->withActiveLoansCount()
            ->findOrFail($bookId);
    }

    /**
     * Atualiza um livro.
     */
    public function update(Book $book, array $data): Book
    {

        if (($data['cover_image'] ?? null) instanceof UploadedFile) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }

            $data['cover_image'] = $data['cover_image']->store('book-covers', 'public');
        } else {
            unset($data['cover_image']);
        }

        $book->update($data);

        return $book;
    }

    /**
     * Remove um livro.
     */
    public function delete(Book $book): void
    {
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }
        $book->delete();
    }
}