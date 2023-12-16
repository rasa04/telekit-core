<?php

namespace Core\Responses;

use Core\Controllers;
use Core\Env;
use Core\Methods\DeleteMessage;
use Core\Methods\Document;
use Core\Methods\SendInvoice;
use Core\Methods\MediaGroup;
use Core\Methods\Message;
use Core\Methods\Photo;

abstract class Trigger
{
    use Controllers;
    use Env;

    public array $lastMessage;

    public function request(): array
    {
        return $GLOBALS['request'];
    }
    public function sendMessage(): Message
    {
        return new Message;
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
    public function replyMessage(string $text): void
    {
        $this->lastMessage = (new Message)
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

    public function deleteMessage($messageId = null): void
    {
        if ($messageId === null) {
            $messageId = $this->lastMessage['result']['message_id'];
        }
        (new Message())->delete($messageId);
    }
}