<?php

namespace Core\API\Methods;

use Core\API\Types\Update;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GetUpdates extends Method
{
    public const METHOD = 'getUpdates';

    public function pull(int $updateId): array
    {
        try {
            $updates = json_decode(
                json: (new Client())
                    ->get(
                        sprintf('%s%s%s', $this->getMethod(), '?offset=', $updateId),
                        [
                            'headers' => ["Content-Type" => "application/json"],
                            'verify' => false,
                        ]
                    )
                    ->getBody()
                    ->getContents(),
                associative: 1
            )['result'];
        } catch (RequestException $exception) {
            $this->log()->error($exception->getMessage(), $exception->getHandlerContext());
            return [
                'error' => true,
            ];
        }

        return array_map(static fn($update) => new Update(data: $update), $updates);
    }
}