<?php

namespace Core\Exceptions;

use Exception;

class InvalidReactionEmoji extends Exception
{
    protected $message = 'Emoji not found';
    protected $code = 404;
}
