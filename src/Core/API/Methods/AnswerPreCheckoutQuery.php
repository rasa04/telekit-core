<?php

namespace Core\API\Methods;

use Core\Env;
use Exception;
use GuzzleHttp\Client;

class AnswerPreCheckoutQuery extends Method
{
    use Env;

    public function __construct($ok)
    {
        $client = new Client();

        try {
            $client->post("https://api.telegram.org/bot" . self::token() . "/answerPreCheckoutQuery",
                [
                    'headers' => ["Content-Type" => "application/json"],
                    'verify' => false,
                    'json' => [
                        'pre_checkout_query_id' => $GLOBALS['request']['pre_checkout_query']['id'],
                        'ok' => $ok,
                    ]
                ]);
        } catch (Exception $e) {
            $this->log()->info($e->getMessage());
        }
    }
}