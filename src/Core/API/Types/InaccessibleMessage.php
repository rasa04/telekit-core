<?php

namespace Core\API\Types;

/**
 * This object describes a message that was deleted or is otherwise inaccessible to the bot.
 * @link https://core.telegram.org/bots/api#inaccessiblemessage
 */
class InaccessibleMessage extends Type implements MessageInterface
{
    public const DATE = 0;
    protected Chat $chat;
    protected MessageId $messageId;
    public function __construct(array $data)
    {
        $this->set('chat', $data['chat']);
        $this->set('messageId', $data['message_id']);
    }

    public function chat(?Chat $chat = null): Chat
    {
        return $this->set('chat', $chat);
    }

    public function messageId(MessageId|int|null $messageId = null): MessageId|int|null
    {
        if ($messageId === null) {
            return $this->get('messageId')->messageId();
        }

        return $this->set(
            propertyName: 'messageId',
            value: is_int($messageId) ? new MessageId($messageId) : $messageId
        );
    }

    public function date(): int
    {
        return self::DATE;
    }
}
