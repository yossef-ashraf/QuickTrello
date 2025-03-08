<?php

namespace QuickTrello\Exceptions;

use Exception;

class TrelloException extends Exception
{
    protected $statusCode;

    public function __construct($message, $statusCode = 500, \Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->statusCode = $statusCode;
    }

    
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}