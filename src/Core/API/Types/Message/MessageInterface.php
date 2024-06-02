<?php

namespace Core\API\Types\Message;

use Core\API\Types\Chat;
use Core\API\Types\MessageId;

/**
 * Interface for different message types
 */
interface MessageInterface
{
    public function chat(?Chat $chat = null): Chat;
    public function messageId(MessageId|int|null $messageId = null): MessageId|int|null;
    public function date(): int;
}
