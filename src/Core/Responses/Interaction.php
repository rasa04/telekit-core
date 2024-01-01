<?php

namespace Core\Responses;

use Core\API\Methods\AnswerInlineQuery;
use Core\Helpers;
use Core\Env;

abstract class Interaction
{
    use Helpers;
    use Env;

    private array $options = [];
    public string $requestQuery;


    public function answerInlineQuery(): AnswerInlineQuery
    {
        return new AnswerInlineQuery;
    }
}