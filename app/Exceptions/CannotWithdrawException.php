<?php

namespace App\Exceptions;

use Exception;

class CannotWithdrawException extends Exception
{
    public function __construct()
    {
        parent::__construct('Could not withdraw the desired amount.');
    }
}
