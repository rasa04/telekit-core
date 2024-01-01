<?php

namespace Core\Exceptions;

use Exception;

class InvalidTypeException extends Exception
{
    protected $message = 'Invalid API type';
}