<?php

namespace Core\Interface;

use Core\API\Types\Message\Message;

interface Trigger
{
    public function __construct(array $request, ?Message $message);
}
