<?php

namespace Core\API\Types\Message;

use Core\API\Types\Chat;
use Core\API\Types\MessageId;

/**
 * This object describes a message that was deleted or is otherwise inaccessible to the bot.
 * @link https://core.telegram.org/bots/api#inaccessiblemessage
 */
class InaccessibleMessage extends MessageAbstract
{
    public const DATE = 0;
    protected Chat $chat;
    protected MessageId $messageId;
    public function __construct(array $data)
    {
        parent::__construct(
            chat: $data['chat'],
            messageId: $data['message_id'] ?? null,
            date: InaccessibleMessage::DATE
        );
    }

    public function date(): int
    {
        return self::DATE;
    }
}
