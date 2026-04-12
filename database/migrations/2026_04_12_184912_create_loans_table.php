<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();

            // Usuário responsável pelo empréstimo
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Livro emprestado
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();

            // Data em que o empréstimo foi realizado
            $table->dateTime('loan_date');

            // Prazo máximo para devolução
            $table->dateTime('due_date');

            // Data de devolução, quando o livro for devolvido
            $table->dateTime('returned_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};