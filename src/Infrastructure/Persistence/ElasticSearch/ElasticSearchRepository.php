<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\ElasticSearch;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Psr\Log\LoggerInterface;

class ElasticSearchRepository
{
    private Client $client;
    private LoggerInterface $logger;

    public function __construct(string $host, LoggerInterface $logger)
    {
        $this->client = ClientBuilder::create()
            ->setHosts(['host' => $host])
            ->build();
        $this->logger = $logger;
    }

    public function client(): Client
    {
        return $this->client;
    }

    protected function persist(
        string $id,
        string $index,
        array $body
    ): void {
        $parameters = [
            'index' => $index,
            'type'  => '_doc',
            'id'    => $id,
            'body'  => $body,
        ];

        try {
            $this->client->index($parameters);
        } catch (\Exception $exception) {
            $this->logger->error(sprintf('ElasticSearch Error: [%s] %s', $exception->getCode(), $exception->getMessage()));
        }
    }

    protected function modify(
        string $id,
        string $index,
        array $body
    ): void {
        $parameters = [
            'index'   => $index,
            'type'    => '_doc',
            'id'      => $id,
            'body'    => ['doc' => $body],
            'refresh' => true,
        ];

        try {
            $this->client->update($parameters);
        } catch (\Exception $exception) {
            $this->logger->error(sprintf('ElasticSearch Error: [%s] %s', $exception->getCode(), $exception->getMessage()));
        }
    }

    protected function get(string $id, string $index): array
    {
        $parameters = [
            'index' => $index,
            'id'    => $id,
        ];

        try {
            $source = $this->client->get($parameters);
            $source['_source']['index'] = $source['_index'];
            $source['_source']['id'] = $source['_id'];

            return $source['_source'];
        } catch (Missing404Exception $exception) {
            $this->logger->error(sprintf('Notification with id "%s" not found in the next index: "%s"', $index, $id));

            return [];
        }
    }

    protected function remove(string $index, string $id): void
    {
        $parameters = [
            'index' => $index,
            'id'    => $id,
        ];

        try {
            $this->client->delete($parameters);
        } catch (\Exception $exception) {
            $this->logger->error(sprintf('ElasticSearch Error: [%s] %s', $exception->getCode(), $exception->getMessage()));
        }
    }
}
