<?php

namespace Core\Interface;

use Core\API\Types\CallbackQuery;
use Core\API\Types\Message\Message;

interface Middleware
{
    public function handle(array $request, ?Message $message, ?CallbackQuery $callbackQuery): void;
}
