<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiClient\ManageEngine;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;

class ManageEngineClient
{
    private Client $client;
    private string $apiKey;
    private LoggerInterface $logger;

    public function __construct(
        string $manageEngineApiHost,
        string $manageEngineApiKey,
        LoggerInterface $logger
    ) {
        $this->client = new Client(['base_uri' => $manageEngineApiHost]);
        $this->apiKey = $manageEngineApiKey;
        $this->logger = $logger;
    }

    public function __invoke(string $businessUnit, string $service, array $params): ?array
    {
        $uri = sprintf('/api/json/%s?apikey=%s', $service, $this->apiKey);
        $params = array_merge($params, ['businessUnit' => $businessUnit]);

        try {
            $response = $this->client->request('POST', $uri, ['form_params' => $params]);
            $arrayResponse = json_decode($response->getBody()->getContents(), true);

            $result = $arrayResponse['response']['result'];
            if (strtolower($result['status']) !== 'success') {
                $this->logger->error($result['statusmessage'], $result);
            }

            return $arrayResponse;
        } catch (ConnectException | RequestException $exception) {
            $this->logger->error('Error connecting', [
                'exception' => $exception,
                'response'  => json_encode($exception->getResponse()),
            ]);
        }

        return null;
    }
}
