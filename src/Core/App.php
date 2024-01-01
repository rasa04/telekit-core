<?php
namespace Core;

use Core\API\Types\CallbackQuery;
use Core\API\Types\Message;
use Core\Storage\Storage;

class App
{
    use Controllers;
    use Env;

    private static array $triggers;
    private static array $callbackData;
    private static array $inlineQueries;
    private static array $games;
    private static array $voices;
    private static array $invoices;
    private static array $middlewares;
    private static array $defaults = [
        'trigger' => null,
        'inline_query' => null,
    ];

    private bool $isHandled = false;
    private array $request = [];
    private ?Message $message = null;
    private ?CallbackQuery $callbackQuery = null;
    private int $updateId;

    private function setVariables(): void
    {
        $request = json_decode(file_get_contents('php://input'), true);
        if (isset($GLOBALS['request'])) { // Polling
            $this->request = $GLOBALS['request'];
        } elseif (!empty($request)) { // Hooks
            $this->request = $request;
        } else { // Admin
            require_once sprintf('%s%s',$this->appPath(), '/admin.php');
            return;
        }

        $this->updateId = $request['update_id'];

        if (isset($this->request['message'])) {
            $this->message = new Message($this->request['message']);
        }
        if (isset($this->request['callback_query'])) {
            $this->callbackQuery = new CallbackQuery($this->request['callback_query']);
        }
    }

    public function handle(bool $writeLogs = true, bool $saveDataToJson = true) : void
    {
        date_default_timezone_set($this->time_zone());
        ini_set('error_reporting', E_ALL);
        ini_set('allow_url_fopen', 1);
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);

        $this->setVariables();
        $this->runMiddlewares();

        if ($writeLogs && $this->request) {
            $this->log()->info('handling message: ', $this->request);
        }
        if ($saveDataToJson && $this->request){
            Storage::save($this->request);
        }
        $this->match();
    }

    public function runMiddlewares(): void
    {
        foreach(static::$middlewares as $middleware) {
            (new $middleware)
                ->handle(
                    $this->request,
                    $this->message ?? null,
                    $this->callbackQuery ?? null
                );
        }
    }
    public static function middlewares(array $middlewares): App
    {
        static::$middlewares = $middlewares;
        return new static;
    }
    public static function triggers(array $triggers, ?string $default = null): App
    {
        static::$triggers = $triggers;
        if ($default !== null) {
            self::$defaults['trigger'] = $default;
        }
        return new static;
    }

    public static function callbacks(array $callbackData): App
    {
        static::$callbackData = $callbackData;
        return new static;
    }

    /**
     * You can trace and response to all queries first in Inlines\DefaultAct class
     * Be careful that the new classes for processing inline Queries do not contradict each other
     * use php regex without specifying any delimiters
     * @param $inlineQueries : array
     * @return App : object context
     */
    public static function inlineQueries(array $inlineQueries, ?string $default = null): App
    {
        static::$inlineQueries = $inlineQueries;
        if ($default !== null) {
            self::$defaults['inline_query'] = $default;
        }
        return new static;
    }

    public static function games(array $games): App
    {
        static::$games = $games;
        return new static;
    }

    public static function voices(array $voices): App
    {
        static::$voices = $voices;
        return new static;
    }
    public static function invoices(array $invoices): App
    {
        static::$invoices = $invoices;
        return new static;
    }

    private function match() : void
    {
        if (isset($this->message) && $this->message->text() !== null) {
            $this->matchTriggers();
        } elseif (isset($this->message) && $this->message->voice() !== null) {
            $this->matchVoices();
        } elseif (isset($this->callbackQuery) && $this->callbackQuery->data() !== null) {
            $this->matchCallbackQueries();
        }
        elseif (isset($this->request['inline_query']['query']))  $this->matchInlineQueries();
        elseif (isset($this->request['pre_checkout_query']))     $this->matchInvoices();
        elseif (isset($this->request['game_short_name']))        $this->matchGames();

        if ($this->isHandled) {
            $this->isHandled = false;
        } else {
            if (isset($this->request['message']['text']) && self::$defaults['trigger'] !== null) {
                new self::$defaults['trigger']($this->request, $this->message ?? null);
            } elseif (isset($this->request['inline_query']['query']) && self::$defaults['inline_query'] !== null) {
                new self::$defaults['inline_query']($this->request, $this->message ?? null);
            }
        }
    }

    private function matchTriggers(): void
    {
        foreach(static::$triggers as $triggerSymbol => $triggerClass) {
            if (preg_match("#$triggerSymbol#", $this->message->text(withLowerCase: true))) {
                new $triggerClass($this->request, $this->message ?? null, $this->callbackQuery ?? null);
                $this->isHandled = true;
            }
        }
    }

    private function matchInlineQueries(): void
    {
        foreach(static::$inlineQueries as $key => $val) {
            if (!preg_match("#$key#", strtolower($this->request['inline_query']['query']))) continue;
            new $val($this->request);
            $this->isHandled = true;
        }
    }

    private function matchCallbackQueries(): void
    {
        foreach(static::$callbackData as $key => $val) {
            if (!preg_match("#$key#", strtolower($this->request['callback_query']['data']))) continue;
            new $val($this->request);
            $this->isHandled = true;
        }
    }

    private function matchGames(): void
    {
//        $iterator = new \ArrayIterator(static::$games);
//        $data_value = $this->request['game_short_name'];
    }

    private function matchVoices(): void
    {
        foreach(static::$voices as $voiceHandler) {
            new $voiceHandler($this->request, $this->message ?? null, $this->callbackQuery ?? null);
            $this->isHandled = true;
        }
    }

    private function matchInvoices(): void
    {
        foreach (static::$invoices as $key => $class) {
            if ($key === $this->request['pre_checkout_query']['invoice_payload']) new $class;
            elseif ($key === $this->request['message']['successful_payment']['invoice_payload']) new $class;
            $this->isHandled = true;
        }
    }
}
