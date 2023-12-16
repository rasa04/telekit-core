<?php

namespace Core\Methods;

use Core\Env;
use Core\Storage\Storage;
use Doctrine\Common\Cache\Psr6\InvalidArgument;
use GuzzleHttp\Exception\RequestException;

class Message extends Action
{
    use Env;

    public const METHOD_SEND = 'sendMessage';
    public const METHOD_DELETE = 'deleteMessage';


    public function send(array $headers = [], bool $writeLogFile = true, bool $saveDataToJson = true) : array
    {
        if (empty($this->response['chat_id'])) {
            throw new InvalidArgument('Chat ID does not exists');
        }
        if (empty($this->response['text'])) {
            throw new InvalidArgument('Text does not exists');
        }

        try {
            $result = $this
                ->client()
                ->post(
                    $this->getEndpoint(method: self::METHOD_SEND),
                    [
                        'headers' => array_merge(["Content-Type" => "application/json"], $headers),
                        'verify' => false,
                        'json' => $this->response,
                    ]
                )
                ->getBody()
                ->getContents();
        } catch (RequestException $e) {
            $this->log($e->getMessage());
            return [];
        }

        //сохраняем то что бот сам отправляет
        if($writeLogFile) $this->log(json_decode($result, 1));
        if($saveDataToJson) Storage::save(json_decode($result, 1));

        return json_decode($result, 1);
    }

    public function delete(int $messageId = null) : void
    {
        $this->client()
            ->post(
                $this->getEndpoint(method: self::METHOD_DELETE),
                [
                    'headers' => ["Content-Type" => "application/json"],
                    'verify' => false,
                    'json' => [
                        'chat_id' => $this->response['chat_id'] ?? $GLOBALS['request']['message']['chat']['id'],
                        'message_id' => $messageId ?? $GLOBALS['request']['message']['message_id']
                    ],
                ]
            );
    }

    /**
     * Unique identifier for the target chat or username of the target channel (in the format @channelusername)
     */
    public function text(string $text) : object
    {
        $this->response['text'] = $text;
        return $this;
    }

    /**
     * A JSON-serialized list of special entities that appear in message text, which can be specified instead of parse_mode
     */
    public function entities(array $entities) : object
    {
        // BETA
        $this->response['entities'] = $entities;
        return $this;
    }

    /**
     * Disables link previews for links in this message
     */
    public function disableWebPagePreview(bool $disable_web_page_preview) : object
    {
        $this->response['disable_web_page_preview'] = $disable_web_page_preview;
        return $this;
    }
}
