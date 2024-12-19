<?php
namespace App\Exceptions;

use Exception;

class InvalidAddressException extends Exception
{
    public function __construct($message = "Endereço inválido", $code = 400)
    {
        parent::__construct($message, $code);
    }
}
