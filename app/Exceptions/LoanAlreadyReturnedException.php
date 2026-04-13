<?php

namespace App\Exceptions;

use Exception;

class LoanAlreadyReturnedException extends Exception
{
    public function __construct(
        string $message = 'Este empréstimo já foi devolvido.'
    ) {
        parent::__construct($message);
    }
}