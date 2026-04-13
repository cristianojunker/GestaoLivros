<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'book_id',
        'loan_date',
        'due_date',
        'returned_at',
        'due_soon_notified_at',
    ];

    protected $casts = [
        'loan_date' => 'datetime',
        'due_date' => 'datetime',
        'returned_at' => 'datetime',
        'due_soon_notified_at' => 'datetime',
    ];

    /**
     * Retorna o usuário responsável pelo empréstimo.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retorna o livro emprestado.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Verifica se o empréstimo ainda está ativo.
     */
    public function isActive(): bool
    {
        return is_null($this->returned_at);
    }

    /**
     * Verifica se o empréstimo está atrasado.
     */
    public function isOverdue(): bool
    {
        return $this->isActive() && $this->due_date->isPast();
    }

    /**
     * Verifica se o empréstimo está próximo do vencimento.
     */
    public function isCloseToDue(): bool
    {
        return $this->isActive()
            && ! $this->isOverdue()
            && Carbon::now()->diffInHours($this->due_date, false) <= 12;
    }
}