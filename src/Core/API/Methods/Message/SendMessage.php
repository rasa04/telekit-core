<?php

namespace Core\API\Methods\Message;

use Core\API\Methods\Method;
use Core\API\Types\Message\Message;
use Core\Env;
use Core\Exceptions\InvalidRequiredParameterException;
use Core\Storage\Storage;
use GuzzleHttp\Exception\RequestException;

/**
 * Use this method to send text messages. On success, the sent @see Message is returned.
 * @link https://core.telegram.org/bots/api#sendmessage
 */
class SendMessage extends Method
{
    use Env;

    public const METHOD = 'sendMessage';

    /**
     * @throws InvalidRequiredParameterException
     */
    public function handle(bool $writeLogs = true, bool $saveDataToJson = true): ?Message
    {
        if (empty($this->get('response')['chat_id'])) {
            throw new InvalidRequiredParameterException('Chat id does not exists');
        }
        if (empty($this->get('response')['text'])) {
            throw new InvalidRequiredParameterException('Text does not exists');
        }

        try {
            $response = $this->request();
        } catch (RequestException $e) {
            $this->log()->error($e->getMessage(), $e->getTrace());
            return null;
        }

        //сохраняем то что бот сам отправляет
        if($writeLogs) {
            $this->log()->info('Message sent: ', $response);
        }
        if($saveDataToJson) {
            Storage::save($response);
        }

        return new Message($response['result']);
    }

    /**
     * Unique identifier for the target chat or username of the target channel (in the format @channelusername)
     */
    public function text(string $text): SendMessage
    {
        $this->response['text'] = $text;
        return $this;
    }

    /**
     * A JSON-serialized list of special entities that appear in message text, which can be specified instead of parse_mode
     */
    public function entities(array $entities): SendMessage
    {
        $this->response['entities'] = $entities;
        return $this;
    }

    /**
     * Disables link previews for links in this message
     */
    public function disableWebPagePreview(bool $disable_web_page_preview): SendMessage
    {
        $this->response['disable_web_page_preview'] = $disable_web_page_preview;
        return $this;
    }

    /**
     * Mode for parsing entities in the message text. See formatting options for more details.
     */
    public function parseMode(string $parse_mode = 'html'): SendMessage
    {
        $this->response['parse_mode'] = $parse_mode;
        return $this;
    }
}
