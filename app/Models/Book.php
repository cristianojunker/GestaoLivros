<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'title',
        'author',
        'description',
        'cover_image',
        'published_year',
    ];

    /**
     * Retorna o usuário dono do livro.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retorna os empréstimos do livro.
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }

    /**
     * Adiciona a contagem de empréstimos ativos na consulta.
     */
    public function scopeWithActiveLoansCount(Builder $query): Builder
    {
        return $query->withCount([
            'loans as active_loans_count' => function ($query) {
                $query->whereNull('returned_at');
            }
        ]);
    }

    /**
     * Filtra apenas livros disponíveis.
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->whereDoesntHave('loans', function ($query) {
            $query->whereNull('returned_at');
        });
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        if (! $this->cover_image) {
            return null;
        }

        return asset('storage/' . $this->cover_image);
    }
}