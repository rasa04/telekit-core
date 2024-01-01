<?php

namespace Core\API\Types;

/**
 * This object represents an incoming callback query from a callback button in an inline keyboard.
 * If the button that originated the query was attached to a message sent by the bot, the field message will be present.
 * If the button was attached to a message sent via the bot (in inline mode), the field inline_message_id will be present.
 * Exactly one of the fields data or game_short_name will be present.
 *
 * @link https://core.telegram.org/bots/api#callbackquery
 */
class CallbackQuery extends Type
{
    protected string $id;
    protected User $from;
    protected ?Message $message;
    protected ?string $inlineMessageId;
    protected string $chatInstance;
    protected ?string $data;
    protected ?string $gameShortName;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->from = new User($data['from']);
        $this->message = isset($data['message']) ? new MaybeInaccessibleMessage($data['message']) : null;
        $this->inlineMessageId = $data['inline_message_id'] ?? null;
        $this->chatInstance = $data['chat_instance'];
        $this->data = $data['data'] ?? null;
        $this->gameShortName = $data['game_short_name'] ?? null;
    }

    public function data(string $data = null): string
    {
        return $this->set($data);
    }

    public function toArray(): array
    {
        $callbackQuery = [
            'id' => $this->id,
            'from' => $this->from->toArray(),
            'chat_instance' => $this->chatInstance,
        ];

        if ($this->data !== null) {
            $callbackQuery['data'] = $this->get('data');
        }
        if ($this->inlineMessageId) {
            $callbackQuery['inline_message_id'] = $this->get('inlineMessageId');
        }
        if ($this->gameShortName) {
            $callbackQuery['game_short_name'] = $this->get('gameShortName');
        }
        if ($this->message) {
            $callbackQuery['message'] = $this->get('message')->toArray();
        }

        return $callbackQuery;
    }
}
