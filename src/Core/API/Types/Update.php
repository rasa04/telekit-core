<?php

namespace Core\API\Types;

use Core\API\Types\Message\Message;
use Core\API\Types\Message\MessageReactionCountUpdated;
use Core\API\Types\Message\MessageReactionUpdated;

/**
 * This object represents an incoming update.
 * At most one of the optional parameters can be present in any given update.
 * @link https://core.telegram.org/bots/api#update
 */
class Update extends Type
{
    protected int $updateId;
    protected ?Message $message = null;
    protected ?Message $editedMessage = null;
    protected ?Message $channelPost = null;
    protected ?Message $editedChannelPost = null;
    protected ?MessageReactionUpdated $messageReactionUpdated = null;
    protected ?MessageReactionCountUpdated $messageReactionCountUpdated = null;

    public function __construct(array $data)
    {
        $this->set('updateId',  $data['update_id']);
        if (isset($data['message'])) {
            $this->set('message', new Message($data['message']));
        }
        if (isset($data['edited_message'])) {
            $this->set('editedMessage', new Message($data['edited_message']));
        }
        if (isset($data['channel_post'])) {
            $this->set('channelPost', new Message($data['channel_post']));
        }
        if (isset($data['edited_channel_post'])) {
            $this->set('editedChannelPost', new Message($data['edited_channel_post']));
        }
        if (isset($data['message_reaction'])) {
            $this->set('messageReactionUpdated', $data['message_reaction']);
        }
        if (isset($data['message_reaction_count'])) {
            $this->set('messageReactionCountUpdated', $data['message_reaction_count']);
        }
    }

    public function updateId(?int $id = null): ?int
    {
        return $this->set('updateId', $id);
    }

    public function message(?Message $message = null): ?Message
    {
        return $this->set('message', $message);
    }
}
