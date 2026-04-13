<?php

namespace App\Exceptions;

use Exception;

class BookUnavailableForLoanException extends Exception
{
    public function __construct(
        string $message = 'Este livro não está disponível para empréstimo.'
    ) {
        parent::__construct($message);
    }
}