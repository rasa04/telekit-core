<?php

namespace Core\Interface;

use Core\API\Types\Message;

interface Middleware
{
    public function handle(array $request, ?Message $message): void;
}
