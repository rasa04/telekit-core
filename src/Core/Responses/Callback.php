<?php

namespace Core\Responses;

use Core\API\Methods\Message\SendMessage;
use Core\Helpers;
use Core\Env;

abstract class Callback
{
    use Helpers;
    use Env;

    public function message(): SendMessage
    {
        return new SendMessage();
    }
}