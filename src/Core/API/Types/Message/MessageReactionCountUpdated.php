<?php

namespace Core\API\Types\Message;

use Core\API\Types\ReactionCount;

class MessageReactionCountUpdated extends MessageAbstract
{
    /** @var ReactionCount[] */
    protected array $reactions = [];

    public function __construct(array $data)
    {
        parent::__construct(
            chat: $data['chat'],
            messageId: $data['message_id'] ?? null,
            date: $data['date'] ?? InaccessibleMessage::DATE
        );
    }

    /**
     * @param ReactionCount[]|null $reactions
     *
     * @return ReactionCount[]
     */
    public function reactions(?array $reactions = null): array
    {
        return $this->set('reactions', $reactions);
    }
}