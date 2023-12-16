<?php

namespace Core\Console\Commands\Make;

use Core\Env;

trait Paths
{
    use Env;
    public static function samplesPath(): string
    {
        return __DIR__ . "/Core/Console/Samples/";
    }

    public static function responsesPath(): string
    {
        return self::appPath() ."Responses/";
    }

    public static function databasePath(): string
    {
        return self::appPath() . "database/";
    }
}