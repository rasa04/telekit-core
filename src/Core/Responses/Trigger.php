<?php

namespace Core\Responses;

use Core\API\Methods\Document;
use Core\API\Methods\MediaGroup;
use Core\API\Methods\Message\DeleteMessage;
use Core\API\Methods\Message\SendMessage;
use Core\API\Methods\Photo;
use Core\API\Methods\SendInvoice;
use Core\API\Types\Message;
use Core\Exceptions\InvalidRequiredParameterException;
use Core\Helpers;
use Core\Env;

abstract class Trigger
{
    use Helpers;
    use Env;

    public Message $lastMessage;

    public function request(): array
    {
        return $GLOBALS['request'];
    }
    public function sendMessage(): SendMessage
    {
        return new SendMessage;
    }
    public function photo(): Photo
    {
        return new Photo;
    }
    public function document(): Document
    {
        return new Document;
    }
    public function mediaGroup(): MediaGroup
    {
        return new MediaGroup;
    }

    /**
     * @throws InvalidRequiredParameterException
     */
    public function replyMessage(string $text): void
    {
        $this->lastMessage = (new SendMessage)
            ->chatId($GLOBALS['request']['message']['chat']['id'] ?? $this->lastMessage?->chat()->id())
            ->text($text)
            ->parseMode()
            ->handle();
    }

    public function requestMessage(): array
    {
        return $GLOBALS['request']['message'];
    }

    public function sendInvoice(): SendInvoice
    {
        return new SendInvoice;
    }

    public function deleteMessage($messageId = null): void
    {
        if ($messageId === null) {
            $messageId = $this->lastMessage->messageId();
        }
        (new DeleteMessage())->handle($messageId);
    }
}