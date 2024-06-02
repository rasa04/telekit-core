<?php

namespace Core\API\Types\Message;

use Core\API\Types\Chat;
use Core\API\Types\MessageId;
use Core\API\Types\User;

/**
 * This object represents a change of a reaction on a message performed by a user.
 * @link https://core.telegram.org/bots/api#messagereactionupdated
 */
class MessageReactionUpdated extends MessageAbstract
{
    private Chat $chat;
    private ?MessageId $messageId = null;
    private int $date;
    private ?User $user = null;
    private ?Chat $actorChat = null;
    private ?array $oldReaction = null;
    private ?array $newReaction = null;

    public function __construct(array $data)
    {
        parent::__construct(
            chat: $data['chat'],
            messageId: $data['message_id'] ?? null,
            date: $data['date'] ?? InaccessibleMessage::DATE
        );

        if (isset($data['user'])) {
            $this->set('user', new User($data['user']));
        }
        if (isset($data['actor_chat'])) {
            $this->set('actorChat',  new Chat($data['actor_chat']));
        }
        if (isset($data['old_reaction'])) {
            $this->set('oldReaction', $data['old_reaction']);
        }
        if (isset($data['new_reaction'])) {
            $this->set('newReaction', $data['new_reaction']);
        }
    }

    public function user(?User $user = null): User
    {
        return $this->set('user', $user);
    }

    public function actorChat(?Chat $actorChat = null): Chat
    {
        return $this->set('actorChat', $actorChat);
    }

    public function oldReaction(?array $oldReaction = null): ?array
    {
        return $this->set('oldReaction', $oldReaction);
    }

    public function newReaction(?array $newReaction = null): ?array
    {
        return $this->set('newReaction', $newReaction);
    }
}
