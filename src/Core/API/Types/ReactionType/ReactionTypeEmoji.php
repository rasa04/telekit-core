<?php

namespace Core\API\Types\ReactionType;

use Core\API\Types\Type;
use Core\Exceptions\InvalidReactionEmoji;

/**
 * The reaction is based on an emoji.
 * @link https://core.telegram.org/bots/api#reactiontypeemoji
 */
class ReactionTypeEmoji extends Type implements ReactionTypeInterface
{
    public const EMOJIS = [
        "👍", "👎", "❤", "🔥", "🥰", "👏", "😁", "🤔", "🤯", "😱",
        "🤬", "😢", "🎉", "🤩", "🤮", "💩", "🙏", "👌", "🕊", "🤡",
        "🥱", "🥴", "😍", "🐳", "❤‍🔥", "🌚", "🌭", "💯", "🤣", "⚡",
        "🍌", "🏆", "💔", "🤨", "😐", "🍓", "🍾", "💋", "🖕", "😈",
        "😴", "😭", "🤓", "👻", "👨‍💻", "👀", "🎃", "🙈", "😇", "😨",
        "🤝", "✍", "🤗", "🫡", "🎅", "🎄", "☃", "💅", "🤪", "🗿",
        "🆒", "💘", "🙉", "🦄", "😘", "💊", "🙊", "😎", "👾", "🤷‍♂",
        "🤷", "🤷‍♀", "😡",
    ];

    protected string $type;
    protected string $emoji;

    /**
     * @throws InvalidReactionEmoji
     */
    public function __construct(string $type, string $emoji)
    {
        $this->set('type', $type);
        if (in_array($emoji, self::EMOJIS, true)) {
            $this->set('emoji', $emoji);
        } else {
            throw new InvalidReactionEmoji;
        }
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
