<?php

namespace Core\Interface;

use Core\Entities\Message;

interface Trigger
{
    public function __construct(array $request, ?Message $message);
}
