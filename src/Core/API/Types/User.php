<?php

namespace Core\API\Types;

/**
 * This object represents a Telegram user or bot.
 * @link https://core.telegram.org/bots/api#user
 */
class User extends Type
{
    private int $id;
    private string $firstName;
    private ?string $lastName;
    private ?string $username;
    private ?string $languageCode;

    public function __construct(array $data)
    {
        $this->set('id', $data['id']);
        $this->set('firstName', $data['first_name']);
        $this->set('lastName', $data['last_name'] ?? null);
        $this->set('username', $data['username'] ?? null);
        $this->set('languageCode', $data['language_code'] ?? null);
    }

    public function id(?string $id = null): int
    {
        return $this->set('id', $id);
    }

    public function firstName(?string $firstName = null): string
    {
        return $this->set('firstName', $firstName);
    }

    public function lastName(?string $lastName = null): ?string
    {
        return $this->set('lastName', $lastName);
    }

    public function username(?string $username = null): ?string
    {
        return $this->set('username', $username);
    }

    public function languageCode(string $languageCode = null): ?string
    {
        return $this->set('languageCode', $languageCode);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->get('id'),
            'first_name' => $this->get('firstName'),
            'last_name' => $this->get('lastName'),
            'username' => $this->get('username'),
            'language_code' => $this->get('languageCode'),
        ];
    }
}
