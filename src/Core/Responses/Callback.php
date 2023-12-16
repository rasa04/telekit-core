<?php

namespace Core\Responses;

use Core\Controllers;
use Core\Env;
use Core\Methods\Message;

class Callback
{
    use Controllers;
    use Env;

    public function message(): Message
    {
        return new Message();
    }
}