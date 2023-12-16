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

class Trigger
{
    use Controllers;
    use Env;

    public array $lastMessage;
    private array $options = [];

    public function __construct(callable $defaultAction = null)
    {
        if ($defaultAction !== null) {
            $this->options['default_action'] = $defaultAction;
        }
    }

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

    public function deleteMessage($message_id = null): void
    {
        if ($message_id === null) {
            $message_id = $this->lastMessage['result']['message_id'];
        }
        (new DeleteMessage())->delete($message_id);
    }

    public function defaultAction($request): void
    {
        $requestMessage = $request['message'];

        // If user passed self default action, run it.
        if (isset($this->options['default_action'])) {
            $this->options['default_action']($requestMessage);
            return;
        }

//        if ($request['message']['chat']['id'] === $request['message']['from']['id']) {
//            new OpenAI($request);
//        }
    }
}