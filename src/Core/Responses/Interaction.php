<?php

namespace Core\Responses;

use Core\Controllers;
use Core\Env;
use Core\Methods\AnswerInlineQuery;

class Interaction
{
    use Controllers;
    use Env;

    private array $options = [];
    public string $requestQuery;

    public function __construct(callable $defaultAction = null)
    {
        if ($defaultAction !== null) {
            $this->options['default_action'] = $defaultAction;
        }
    }

    public function answerInlineQuery(): AnswerInlineQuery
    {
        return new AnswerInlineQuery;
    }

    public function defaultAction($request): void
    {
        $this->requestQuery = $request['inline_query']['query'];

        // If user passed self default action, run it.
        if (isset($this->options['default_action'])) {
            $this->options['default_action']($this->requestQuery);
            return;
        }

        if (empty($this->requestQuery))
        {
            $dices = [
                'd20' => 20,
                'd4' => 4,
                'd100' => 100
            ];

            $result = [];
            $i = 0;
            foreach ($dices as $key => $value) {
                $result[] = [
                    "type" => "article",
                    "id" => $i++,
                    "title" => "Бросить " . "$key",
                    "description" => "Удачной игры!",
                    "input_message_content" => [
                        "message_text" => "<code>d$value</code> <b>результат:</b> <code>" . rand(1, $value) . "</code>",
                        "parse_mode" => "HTML"
                    ]
                ];
            }

            $this->answerInlineQuery()
                ->inline_query_id($request['inline_query']['id'])
                ->results($result)
                ->cache_time(1)
                ->is_personal(true)
                ->send(false, false);
        }
    }
}