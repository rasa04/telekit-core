<?php

namespace Core\API\Types;

/**
 * This object represents a message.
 * @link https://core.telegram.org/bots/api#message
 */
class Message extends Type implements MessageInterface
{
    protected int $date;
    protected Chat $chat;
    protected ?string $text = null;
    protected ?User $from = null;
    protected ?MessageId $messageId = null;
    protected ?Voice $voice = null;
    protected ?array $entities = null;

    public function __construct(array $data)
    {
        $this->set('date', $data['date']);
        $this->set(propertyName: 'chat', value: new Chat(data: $data['chat']));
        if (isset($data['message_id'])) {
            $this->set(propertyName: 'messageId', value: new MessageId($data['message_id']));
        }
        if (isset($data['text'])) {
            $this->set(propertyName: 'text', value: $data['text']);
        }
        if (isset($data['from']) && is_array($data['from'])) {
            $this->set(propertyName: 'from', value: new User(data: $data['from']));
        }
        if (isset($data['voice']) && is_array($data['voice'])) {
            $this->set(propertyName: 'voice', value: new User(data: $data['voice']));
        }
        if (isset($data['entities']) && is_array($data['entities'])) {
            $entities = [];
            foreach ($data['entities'] as $entity) {
                $entities[] = new MessageEntity(data: $entity);
            }
            $this->set(propertyName: 'entities', value: $entities);
        }

        return $this;
    }

    public function chat(?Chat $chat = null): Chat
    {
        return $this->set('chat', $chat);
    }

    public function from(?User $from = null): ?User
    {
        return $this->set('from', $from);
    }

    public function text(?string $text = null, bool $withLowerCase = false): ?string
    {
        $text = $this->set(propertyName: 'text', value: $text);

        return $withLowerCase && $text !== null ? strtolower($text) : $text;
    }

    public function date(): int
    {
        return $this->get('date');
    }

    public function entities(?array $entities = null): array
    {
        if ($entities !== null) {
            $messageEntities = [];
            foreach ($entities as $entity) {
                if ($entity instanceof MessageEntity) {
                    $messageEntities[] = $entity;
                }
            }
            $this->entities = $messageEntities;
        }
        return $this->entities ?? [];
    }

    public function voice(?Voice $voice = null): ?Voice
    {
        return $this->set('voice', $voice);
    }

    public function messageId(MessageId|int|null $messageId = null): MessageId|int|null
    {
        if ($messageId === null) {
            return $this->messageId?->messageId();
        }

        return $this->set(
            propertyName: 'messageId',
            value: is_int($messageId) ? new MessageId($messageId) : $messageId
        );
    }

    public function toArray(): array
    {
        $message = [
            'text' => $this->text(),
            'from' => $this->from(),
            'chat' => $this->chat(),
        ];

        if ($this->messageId !== null) {
            $message['message_id'] = $this->messageId();
        }
        if (is_array($this->entities) && !empty($this->entities)) {
            $message['entities'] = array_map(
                fn(MessageEntity $entity): array => $entity->toArray(),
                $this->entities
            );
        }

        return $message;
    }
}
