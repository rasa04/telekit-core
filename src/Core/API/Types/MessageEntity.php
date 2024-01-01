<?php

namespace Core\API\Types;

/**
 * This object represents one special entity in a text message. For example, hashtags, usernames, URLs, etc.
 * @link https://core.telegram.org/bots/api#messageentity
 */
class MessageEntity extends Type
{
    public const TYPES = [
        'mention',
        'hashtag',
        'cashtag',
        'bot_command',
        'url',
        'email',
        'phone_number',
        'bold',
        'italic',
        'underline',
        'strikethrough',
        'spoiler',
        'blockquote',
        'code',
        'pre',
        'text_link',
        'text_mention',
        'custom_emoji'
    ];

    private string $type;
    private int $offset;
    private int $length;
    private ?string $url;
    private ?User $user;
    private ?string $language;

    public function __construct(array $data)
    {
        $this->set('type', $data['type']);
        $this->set('offset', $data['offset']);
        $this->set('length', $data['length']);
        $this->set('url', $data['url'] ?? null);
        $this->set('user', isset($data['user']) ? new User($data['user']) : null);
        $this->set('language', $data['language'] ?? null);
    }

    public function type(?string $type = null): string
    {
        return $this->set('type', $type);
    }

    public function offset(?int $offset = null): int
    {
        return $this->set('offset', $offset);
    }

    public function length(?int $length = null): int
    {
        return $this->set('length', $length);
    }

    public function url(?string $url = null): ?string
    {
        return $this->set('url', $url);
    }

    public function user(?User $user = null): ?User
    {
        return $this->set('user', $user);
    }

    public function language(?string $language = null): ?string
    {
        return $this->set('language', $language);
    }

    public function toArray(): array
    {
        $entity = [
            'type' => $this->get('type'),
            'offset' => $this->get('offset'),
            'length' => $this->get('length'),
        ];

        if ($this->get('url') !== null) {
            $entity['url'] = $this->get('url');
        }
        if ($this->get('user') !== null) {
            $entity['user'] = $this->get('user');
        }
        if ($this->get('language') !== null) {
            $entity['language'] = $this->get('language');
        }

        return $entity;
    }
}
