<?php

namespace Core\Responses;

use Core\API\Methods\AnswerPreCheckoutQuery;
use Core\API\Methods\Message\SendMessage;

abstract class Invoice
{
    public static function invoice(): array
    {
        return $GLOBALS['request']['pre_checkout_query'];
    }

    public static function answerPreCheckoutQuery($ok): void
    {
        new AnswerPreCheckoutQuery($ok);
    }

    public function replyMessage(string $message): void
    {
        (new SendMessage)
            ->chatId($GLOBALS['request']['pre_checkout_query']['from']['id'])
            ->text($message)
            ->send();
    }

    public static function isPreCheckout(): bool
    {
        return isset($GLOBALS['request']['pre_checkout_query']['invoice_payload']);
    }

    public static function isSuccessful(): bool
    {
        return isset($GLOBALS['request']['message']['successful_payment']);
    }
}