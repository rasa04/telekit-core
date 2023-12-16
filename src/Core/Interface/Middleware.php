<?php

namespace Core\Interface;

use Core\Entities\Message;

interface Middleware
{
    public function handle(array $request, Message $message): void;
}
