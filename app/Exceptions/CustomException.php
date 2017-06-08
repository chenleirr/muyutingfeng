<?php

namespace App\Exceptions;

use Exception;

class CustomException extends Exception
{
    private $logLevel;

    public function __construct($message = "", $code = 0, Exception $previous = null, $logLevel = 'error')
    {
        $this->message = $message;
        $this->code = ($code == 0 ? config('custom_code.server_error.code') : $code);
        $this->logLevel = $logLevel;
    }

    public function getLogLevel()
    {
        return $this->logLevel;
    }
}
