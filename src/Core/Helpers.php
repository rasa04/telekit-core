<?php
namespace Core;

use Core\API\Methods\Message\SendMessage;
use Core\Exceptions\InvalidRequiredParameterException;
use Core\Storage\Storage;
use Database\models\Chat;
use GuzzleHttp\Client;
use JetBrains\PhpStorm\NoReturn;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

trait Helpers
{
    use Env;

    public function log(string $file = "logs.log") : Logger
    {
        return (new Logger('telekit-core'))
            ->pushHandler(
                new StreamHandler(sprintf('%s%s',$this->storage_path(), $file))
            );
    }

    public function transcript($fileLink)
    {
        $client = new Client();
        return json_decode(
            json: $client->post(
                uri: 'https://api.openai.com/v1/audio/transcriptions',
                options: [
                    'headers' => ['Authorization' => 'Bearer ' . $this->gpt_token()],
                    'multipart' => [
                        [
                            'name'     => 'file',
                            'contents' => fopen($fileLink, 'r')
                        ],
                        [
                            'name' => 'model',
                            'contents' => 'whisper-1',
                        ]
                    ],
                    'verify' => false
                ]
            )->getBody()->getContents(),
            associative: 1
        )['text'];
    }

    public function chatGPT3($messages): string
    {
        $client = new Client();
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->gpt_token(),
            ],
            'json' => [
                "model" => "gpt-3.5-turbo",
                "messages" => $messages,
            ],
            'verify' => false,
        ]);

        $result = json_decode($response->getBody()->getContents(), true)['choices'][0]['message']['content'];

        return (strlen($result) < 4000) ? $result : substr($result, 0, 4096);
    }

    public function saveFile(bool $withLog = false) : array
    {
        $request = json_decode(file_get_contents('php://input'), true);

        // RECEIVE FILE
        if (!empty($request["message"]["photo"])) {
            $file = [
                "file_id" => $request["message"]["photo"][3]["file_id"],
            ];
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.telegram.org/bot' . $this->token() . "/getFile?" . http_build_query($file),
                CURLOPT_POST => 1,
                CURLOPT_HEADER => 0,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_POSTFIELDS => $request,
                CURLOPT_SSL_VERIFYPEER => 0,
                // CURLOPT_HTTPHEADER => array_merge(array("Content-Type: application/json"), $headers),
            ]);
            
            $result = curl_exec($curl);
            curl_close($curl);

            // записываем ответ в формате PHP массива
            $dataResult = json_decode($result, true);
            // записываем URL необходимого изображения
            $fileUrl = $dataResult["result"]["file_path"];
            // формируем полный URL до файла
            $photoPathTG = "https://api.telegram.org/file/bot" . $this->token() . "/" . $fileUrl;

            if ($withLog) {
                $this->log()->info($photoPathTG);
                Storage::save($request);
            }
            
            // забираем название файла
            $newFilePath = $this->storage_path() . "img/" . explode("/", $fileUrl)[1];
            // сохраняем файл на серсер
            file_put_contents($newFilePath, file_get_contents($photoPathTG));
        }
        return $request;
    }

    public function isAuthorized(): bool
    {
        $chat = Chat::query()
            ->where('chat_id', $GLOBALS['request']['message']['chat']['id'])
            ->first('rights');
        return $chat && $chat->toArray()['rights'] > 0;
    }

    public function chat_is_group(): bool
    {
        return ($GLOBALS['request']['message']['chat']['type'] === 'group'
            || $GLOBALS['request']['message']['chat']['type'] === 'supergroup');
    }

    public function user_language(): string
    {
        return $GLOBALS['request']['message']['from']['language_code'];
    }

    public function message_date($format = 'timestamps', $value = ''): int|string
    {
        if ($format === 'view') {
            return date('Y-m-d H:i:s', $GLOBALS['request']['message']['date']);
        }
        elseif ($format === 'self') {
            return date($value, $GLOBALS['request']['message']['date']);
        }
        else {
            return $GLOBALS['request']['message']['date'];
        }
    }

    /**
     * @throws InvalidRequiredParameterException
     */
    #[NoReturn] public function sendLog(string $data, bool $disable_notification = true) : void
    {
        (new SendMessage)->chatId($GLOBALS['request']['message']['chat']['id']
                ?? $GLOBALS['request']['callback_query']['message']['chat']['id']
                ?? $GLOBALS['request']['callback_query']['from']['id']
                ?? $GLOBALS['request']['inline_query']['from']['id']
                ?? null)
            ->text($data)
            ->disableNotification($disable_notification)
            ->allowSendingWithoutReply()
            ->handle();
        die();
    }
}
