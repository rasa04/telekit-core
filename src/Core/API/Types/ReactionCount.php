<?php

namespace Core\API\Types;

use Core\API\Types\ReactionType\ReactionTypeInterface;

/**
 * Represents a reaction added to a message along with the number of times it was added.
 *
 * @link https://core.telegram.org/bots/api#reactioncount
 */
class ReactionCount extends Type
{
    protected ReactionTypeInterface $type;
    protected int $totalCount;

    public function __construct(ReactionTypeInterface $type, int $totalCount)
    {
        $this->set('type', $type);
        $this->set('totalCount', $totalCount);
    }

    public function type(?ReactionTypeInterface $type = null): ReactionTypeInterface
    {
        return $this->set('type', $type);
    }

    public function totalCount(?int $totalCount = null): int
    {
        return $this->set('totalCount', $totalCount);
    }
}
