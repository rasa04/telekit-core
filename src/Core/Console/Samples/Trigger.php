<?php
namespace Responses\Triggers;

use Core\Responses\Trigger;

class Sample extends Trigger {
    public function __construct($request)
    {
        $this->response()->chatId($request['message']['chat']['id'])->text("text")->send();
    }
}