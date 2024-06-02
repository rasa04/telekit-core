<?php

namespace Core\API\Types\ReactionType;

use Core\API\Types\Type;

/**
 * The reaction is based on a custom emoji.
 * @link https://core.telegram.org/bots/api#reactiontypecustomemoji
 */
class ReactionTypeCustomEmoji extends Type implements ReactionTypeInterface
{
    protected string $type;
    protected string $customEmoji;

    public function __construct(string $type, string $customEmoji)
    {
        $this->set('type', $type);
        $this->set('customEmoji', $customEmoji);
    }

    public function type(string $type)
    {
        return $this->set('type', $type);
    }

    public function emoji(string $emoji): string
    {
        return $this->set('emoji', $emoji);
    }
}
