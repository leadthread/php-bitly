<?php

namespace Zenapply\Bitly\Exceptions;

class BitlyAuthException extends BitlyException
{
    public function __construct($message = "INVALID_LOGIN", $code = 0, Exception $previous = null){
        parent::__construct($message, $code, $previous);
    }
}