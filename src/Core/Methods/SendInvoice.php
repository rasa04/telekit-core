<?php

namespace Core\Methods;

use Core\Env;
use GuzzleHttp\Client;

class SendInvoice
{
    use Env;

    private static int $chatId;
    private static string $title;
    private static string $description;
    private static string $payload;
    private static string $currency;
    private static array $prices;

    public function __construct()
    {
        self::$chatId = $GLOBALS['request']['message']['from']['id'];
    }

    public static function chatId(int $chatId): SendInvoice
    {
        self::$chatId = $chatId;
        return new static;
    }
    public static function title(string $title): SendInvoice
    {
        self::$title = $title;
        return new static;
    }
    public static function description(string $description): SendInvoice
    {
        self::$description = $description;
        return new static;
    }
    public static function payload(string $payload): SendInvoice
    {
        self::$payload = $payload;
        return new static;
    }
    public static function currency(string $currency): SendInvoice
    {
        self::$currency = $currency;
        return new static;
    }
    public static function prices(array $prices): SendInvoice
    {
        self::$prices = $prices;
        return new static;
    }

    public static function send(): void
    {
        $client = new Client();

        $client->post("https://api.telegram.org/bot" . self::token() . "/sendInvoice",
        [
            'headers' => ["Content-Type" => "application/json"],
            'verify' => false,
            'json' => [
                'chat_id' => self::$chatId,
                'title' => self::$title,
                'description' => self::$description,
                'provider_token' => \env('CLICK_TOKEN'),
                'payload' => self::$payload,
                'currency' => self::$currency,
                'prices' => [self::$prices],
            ]
        ]);
    }
}