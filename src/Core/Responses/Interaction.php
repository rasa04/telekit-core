<?php

namespace Core\Responses;

use Core\Controllers;
use Core\Env;
use Core\Methods\AnswerInlineQuery;

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