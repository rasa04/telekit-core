<?php
namespace Responses\Triggers;

use Core\API\Types\Message;
use Core\Responses\Trigger;

class Sample extends Trigger implements \Core\Interface\Trigger {
    public function __construct(array $request, ?Message $message)
    {
        $chatId = $message?->chat()?->id() ?? $request['message']['chat']['id'];
        $this->sendMessage()
            ->chatId($chatId)
            ->text("text")
            ->send();
    }
}