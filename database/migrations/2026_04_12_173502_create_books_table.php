<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();

            // Relaciona o livro ao usuário dono do registro
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Título do livro
            $table->string('title');

            // Autor do livro
            $table->string('author');

            // Descrição opcional
            $table->text('description')->nullable();

            // Ano de publicação opcional
            $table->unsignedSmallInteger('published_year')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};