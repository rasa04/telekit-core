<?php

namespace Core\API\Methods;

use Core\Helpers;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

abstract class Method
{
    use Helpers;

    protected array $response;
    protected const API_ENDPOINT = 'https://api.telegram.org/bot';

    public function set(string $propertyName, mixed $value = null): mixed
    {
        // @todo проверить, будет ли работать если тут property_exists
        if ($value !== null && property_exists($this, $propertyName)) {
            $this->$propertyName = $value;
        }

        return $this->$propertyName;
    }

    public function get(string $propertyName): mixed
    {
        return $this->$propertyName;
    }

    protected function getMethod(): string
    {
        return sprintf('%s%s%s%s',self::API_ENDPOINT, $this->token(), "/", static::METHOD);
    }

    public function request(): array
    {
        return json_decode(
            json: (new Client())
                ->post(
                    $this->getMethod(),
                    [
                        'headers' => ["Content-Type" => "application/json"],
                        'verify' => false,
                        'json' => $this->get('response'),
                    ]
                )
                ->getBody()
                ->getContents(),
            associative: 1
        );
    }

    /**
     * Unique identifier for the target chat or username of the target channel (in the format @channelusername)
     */
    public function chatId(int $id) : object
    {
        $this->response['chat_id'] = $id;
        return $this;
    }

    /**
     * If the message is a reply, ID of the original message
     */
    public function reply_to_message_id(int $reply_to_message_id) : object
    {
        $this->response['reply_to_message_id'] = $reply_to_message_id;
        return $this;
    }

    /**
     * Additional interface options. A JSON-serialized object for an inline keyboard, custom reply keyboard, 
     * instructions to remove reply keyboard or to force a reply from the user.
     */
    public function replyMarkup(array $replyMarkup): Method
    {
        $this->response['reply_markup'] = $replyMarkup;
        return $this;
    }
    
    /**
     * Unique identifier for the target message thread (topic) of the forum; for forum supergroups only
     */
    public function messageThreadId(int $messageThreadId): Method
    {
        $this->response['message_thread_id'] = $messageThreadId;
        return $this;
    }

    /**
     * Sends the message silently. Users will receive a notification with no sound.
     */
    public function disableNotification(bool $disable_notification): Method
    {
        $this->response['disable_notification'] = $disable_notification;
        return $this;
    }

    /**
     * Protects the contents of the sent message from forwarding and saving
     */
    public function protectContent(bool $protectContent): Method
    {
        $this->response['protect_content'] = $protectContent;
        return $this;
    }
    
    /**
     * Pass True if the message should be sent even if the specified replied-to message is not found
     */
    public function allowSendingWithoutReply(): Method
    {
        $this->response['allow_sending_without_reply'] = true;
        return $this;
    }
}