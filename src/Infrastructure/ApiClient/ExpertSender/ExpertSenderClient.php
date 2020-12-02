<?php
declare(strict_types=1);

namespace App\Infrastructure\ApiClient\ExpertSender;

use App\Infrastructure\ApiClient\ExpertSender\Exception\ExpertSenderException;
use App\Infrastructure\ApiClient\ExpertSender\Exception\SubscriberNotFoundException;
use App\Infrastructure\ApiClient\ExpertSender\Request\AddUserToList;
use App\Infrastructure\ApiClient\ExpertSender\Results\ApiResult;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class ExpertSenderClient
{
    private ExpertSender $client;
    private DataTable $applicationDataTable;
    private int $mailingListId;

    public function __construct(
        string $apiUrl,
        string $apiKey,
        int $mailingListId,
        string $applicationTableName
    ) {
        $this->client = new ExpertSender($apiUrl, $apiKey, $this->createHttpClient());
        $this->applicationDataTable = new DataTable($this->client, $applicationTableName);
        $this->mailingListId = $mailingListId;
    }

    public function getSubscriberId(string $email): int
    {
        try {
            $response = $this->client->getUserId($email);
        } catch (ExpertSenderException $exception) {
            if ($exception->getMessage() === 'Can\'t get user id') {
                throw new SubscriberNotFoundException($email);
            }
        }

        if (!$response->isOk()) {
            throw $this->createExceptionFromResponse($response);
        }

        return (int) $response->getId();
    }

    public function addSubscriber(string $email): void
    {
        $request = new AddUserToList();
        $request
            ->setListId($this->mailingListId)
            ->setEmail($email);

        $response = $this->client->addUserToList($request);

        if (!$response->isOk()) {
            throw $this->createExceptionFromResponse($response);
        }
    }

    public function deleteSubscriber(string $email): void
    {
        $response = $this->client->deleteUser($email, $this->mailingListId);

        if (!$response->isOk()) {
            throw $this->createExceptionFromResponse($response);
        }
    }

    public function applicationDataTable(): DataTable
    {
        return $this->applicationDataTable;
    }

    private function createExceptionFromResponse(ApiResult $response): ExpertSenderException
    {
        return new ExpertSenderException(sprintf(
            'Error %s: %s',
            $response->getErrorCode(),
            $response->getErrorMessage()
        ));
    }

    private function createHttpClient(): ClientInterface
    {
        return new Client(['http_errors' => false]);
    }
}
