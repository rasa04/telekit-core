<?php

namespace Core\Responses;

use Core\API\Methods\AnswerInlineQuery;
use Core\Controllers;
use Core\Env;

abstract class Interaction
{
    use Controllers;
    use Env;

    private array $options = [];
    public string $requestQuery;


    public function answerInlineQuery(): AnswerInlineQuery
    {
        return new AnswerInlineQuery;
    }
}