<?php

namespace Core\Exceptions;

use Exception;

class InvalidMethodException extends Exception
{
    protected $message = 'Invalid API method';
}