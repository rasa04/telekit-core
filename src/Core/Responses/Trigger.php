<?php

namespace Core\Responses;

use Core\API\Methods\Document;
use Core\API\Methods\MediaGroup;
use Core\API\Methods\Message\DeleteMessage;
use Core\API\Methods\Message\SendMessage;
use Core\API\Methods\Photo;
use Core\API\Methods\SendInvoice;
use Core\Controllers;
use Core\Env;
use Core\Exceptions\InvalidMethodException;

abstract class Trigger
{
    use Controllers;
    use Env;

    public array $lastMessage;

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
     * @throws InvalidMethodException
     */
    public function replyMessage(string $text): void
    {
        $this->lastMessage = (new SendMessage)
            ->chatId($GLOBALS['request']['message']['chat']['id'])
            ->text($text)
            ->parseMode()
            ->send();
    }

    public function requestMessage(): array
    {
        return $GLOBALS['request']['message'];
    }

    public function sendInvoice(): SendInvoice
    {
        return new SendInvoice;
    }

    /**
     * @throws InvalidMethodException
     */
    public function deleteMessage($messageId = null): void
    {
        if ($messageId === null) {
            $messageId = $this->lastMessage['result']['message_id'];
        }
        (new DeleteMessage())->handle($messageId);
    }
}