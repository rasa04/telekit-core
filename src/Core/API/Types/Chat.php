<?php

namespace Core\API\Types;

/**
 * This object represents a chat.
 * @link https://core.telegram.org/bots/api#chat
 */
class Chat extends Type
{
    public const TYPE_PRIVATE = 'private';
    public const TYPE_GROUP = 'group';
    public const TYPE_SUPERGROUP = 'supergroup';
    protected int $id;
    protected string $type;
    protected ?string $title;
    protected ?string $username;
    protected ?string $firstName;
    protected ?string $lastName;

    public function __construct(array $data)
    {
        $this->set('id', $data['id']);
        $this->set('type', $data['type']);
        $this->set('title', $data['title'] ?? null);
        $this->set('username', $data['username'] ?? null);
        $this->firstName = $data['first_name'] ?? null;
        $this->lastName = $data['last_name'] ?? null;
    }

    public function id(?int $id = null): int
    {
        return $this->set('id', $id);
    }

    public function type(?string $type = null): string
    {
        return $this->set('type', $type);
    }

    public function title(?string $title = null): string
    {
        return $this->set('title', $title);
    }

    public function username(?string $username = null): ?string
    {
        return $this->set('username', $username);
    }

    public function firstName(?string $firstName = null): string
    {
        return $this->set('firstName', $firstName);
    }

    public function lastName(?string $lastName = null): ?string
    {
        return $this->set('lastName', $lastName);
    }

    public function isPrivate(): bool
    {
        return $this->type() === self::TYPE_PRIVATE;
    }

    public function isGroup(): bool
    {
        return $this->type() === self::TYPE_GROUP;
    }

    public function isSupergroup(): bool
    {
        return $this->type() === self::TYPE_SUPERGROUP;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->get('id'),
            'type' => $this->get('type'),
            'title' => $this->get('title'),
            'username' => $this->get('username'),
            'first_name' => $this->get('firstName'),
            'last_name' => $this->get('firstName'),
        ];
    }
}
