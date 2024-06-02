<?php

namespace Core\API\Types\Message;

use Core\API\Types\Chat;
use Core\API\Types\MessageId;
use Core\API\Types\Type;

abstract class MessageAbstract extends Type implements MessageInterface
{
    public function __construct(Chat $chat, MessageId|int|null $messageId, int $date = 0)
    {
        $this->set(propertyName: 'chat', value: $chat);
        if ($messageId !== null) {
            $this->messageId(messageId: new MessageId($messageId));
        }
        $this->set('date', $date);
    }

    public function chat(?Chat $chat = null): Chat
    {
        return $this->set('chat', $chat);
    }

    public function messageId(MessageId|int|null $messageId = null): MessageId|int|null
    {
        if ($messageId === null) {
            return $this->get('messageId')?->messageId();
        }

        return $this->set(
            propertyName: 'messageId',
            value: is_int($messageId) ? new MessageId($messageId) : $messageId
        );
    }

    public function date(): int
    {
        return $this->get('date');
    }
}
