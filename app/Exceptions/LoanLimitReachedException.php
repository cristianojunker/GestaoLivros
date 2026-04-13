<?php

namespace App\Exceptions;

use Exception;

class LoanLimitReachedException extends Exception
{
    public function __construct(
        string $message = 'O usuário já possui 3 livros emprestados no momento.'
    ) {
        parent::__construct($message);
    }
}