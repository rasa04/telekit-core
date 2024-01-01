<?php

namespace Core\API\Types;

/**
 * This object represents a unique message identifier.
 * @link https://core.telegram.org/bots/api#messageid
 */
class MessageId extends Type
{
    protected int $messageId;

    public function __construct(int $messageId)
    {
        $this->set('messageId', $messageId);
    }

    public function messageId(?int $messageId = null): int
    {
        return $this->set('messageId', $messageId);
    }
}
