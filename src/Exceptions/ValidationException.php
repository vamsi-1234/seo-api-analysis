<?php
namespace App\Exceptions;

class ValidationException extends \RuntimeException
{
    protected $code = 422;
    
    public function __construct($message = "Invalid input data", $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}