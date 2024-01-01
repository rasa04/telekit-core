<?php

namespace Core\API\Types;

/**
 * Interface for different message states
 */
interface MessageInterface
{
    public function chat(?Chat $chat = null): Chat;
    public function messageId(MessageId|int|null $messageId = null): MessageId|int|null;
    public function date(): int;
}
