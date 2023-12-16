<?php

namespace Core\Entities;

class Message
{
    public const TYPE_PRIVATE = 'private';
    public const TYPE_GROUP = 'group';
    public const TYPE_SUPERGROUP = 'supergroup';
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getChatID(): int
    {
        return $this->getData()['chat']['id'];
    }
    public function getText(bool $withLowerCase = false)
    {
        return $withLowerCase
            ? strtolower($this->getData()['text'])
            : $this->getData()['text'];
    }
    public function getChatType()
    {
        return $this->getData()['chat']['type'];
    }
    public function getChatTitle()
    {
        return $this->getData()['chat']['title'];
    }
    public function getChatUserName()
    {
        return $this->getData()['chat']['username'];
    }
    public function getUserFirstName()
    {
        return $this->getData()['from']['first_name'];
    }
    public function getUsername(): ?string
    {
        return $this->getData()['from']['username'] ?? null;
    }
    public function getLangCode()
    {
        return $this->getData()['from']['language_code'];
    }

    public function isPrivate(): bool
    {
        return $this->getData()['chat']['type'] === self::TYPE_PRIVATE;
    }
    public function isGroup(): bool
    {
        return $this->getData()['chat']['type'] === self::TYPE_GROUP;
    }
    public function isSupergroup(): bool
    {
        return $this->getData()['chat']['type'] === self::TYPE_SUPERGROUP;
    }
}