<?php

namespace Core\Responses;

use Core\API\Methods\Message\SendMessage;
use Core\Controllers;
use Core\Env;

abstract class Callback
{
    use Controllers;
    use Env;

    public function message(): SendMessage
    {
        return new SendMessage();
    }
}