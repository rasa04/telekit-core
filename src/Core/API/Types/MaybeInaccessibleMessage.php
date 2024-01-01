<?php

namespace Core\API\Types;

/**
 * This object describes a message that can be inaccessible to the bot. It can be one of
 * @link https://core.telegram.org/bots/api#maybeinaccessiblemessage
 */
class MaybeInaccessibleMessage
{
    public function __construct(array $data)
    {
        return $data['date'] === InaccessibleMessage::DATE
            ? new InaccessibleMessage($data)
            : new Message($data);
    }
}
