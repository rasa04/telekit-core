<?php

namespace Core\API\Types;

/**
 * This object represents a voice note.
 * @link https://core.telegram.org/bots/api#voice
 */
class Voice extends Type
{
    protected string $fileId;
    protected string $fileUniqueId;
    protected int $duration;
    protected ?string $mimeType = null;
    protected ?int $fileSize = null;

    public function __construct(array $data)
    {
        $this->set('fileId', $data['file_id']);
        $this->set('fileUniqueId', $data['file_unique_id'] ?? null);
        $this->set('duration', $data['duration']);
        $this->set('mimeType', $data['mime_type'] ?? null);
        $this->set('fileSize', $data['file_size'] ?? null);
    }

    public function fileId(?string $fileId = null): string
    {
        return $this->set('fileId', $fileId);
    }

    public function duration(?int $duration = null): int
    {
        return $this->set('duration', $duration);
    }

    public function mimeType(?string $mimeType = null): ?string
    {
        return $this->set('mimeType', $mimeType);
    }

    public function fileSize(?int $fileSize = null): ?int
    {
        return $this->set('fileSize', $fileSize);
    }
}
