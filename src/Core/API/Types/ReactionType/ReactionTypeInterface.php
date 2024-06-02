<?php

namespace Core\API\Types\ReactionType;

/**
 * This interface describes the type of a reaction. Currently, it can be one of
 * - ReactionTypeEmoji
 * - ReactionTypeCustomEmoji
 * @link https://core.telegram.org/bots/api#reactiontype
 */
interface ReactionTypeInterface
{
    public const TYPES = [
        'emoji',
        'custom_emoji',
    ];

    public function type(string $type);
}
