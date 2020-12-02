<?php

namespace App\Infrastructure\ApiClient\ExpertSender;

use App\Infrastructure\ApiClient\ExpertSender\Entities\Receiver;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\RequestOptions;
use App\Infrastructure\ApiClient\ExpertSender\Chunks\ChunkInterface;
use App\Infrastructure\ApiClient\ExpertSender\Chunks\ColumnChunk;
use App\Infrastructure\ApiClient\ExpertSender\Chunks\ColumnsChunk;
use App\Infrastructure\ApiClient\ExpertSender\Chunks\DataChunk;
use App\Infrastructure\ApiClient\ExpertSender\Chunks\GroupChunk;
use App\Infrastructure\ApiClient\ExpertSender\Chunks\HeaderChunk;
use App\Infrastructure\ApiClient\ExpertSender\Chunks\OrderByChunk;
use App\Infrastructure\ApiClient\ExpertSender\Chunks\OrderByColumnsChunk;
use App\Infrastructure\ApiClient\ExpertSender\Chunks\PrimaryKeyColumnsChunk;
use App\Infrastructure\ApiClient\ExpertSender\Chunks\PropertiesChunk;
use App\Infrastructure\ApiClient\ExpertSender\Chunks\PropertyChunk;
use App\Infrastructure\ApiClient\ExpertSender\Chunks\ReceiverChunk;
use App\Infrastructure\ApiClient\ExpertSender\Chunks\ReceiversChunk;
use App\Infrastructure\ApiClient\ExpertSender\Chunks\SimpleChunk;
use App\Infrastructure\ApiClient\ExpertSender\Chunks\SnippetChunk;
use App\Infrastructure\ApiClient\ExpertSender\Chunks\SnippetsChunk;
use App\Infrastructure\ApiClient\ExpertSender\Chunks\WhereChunk;
use App\Infrastructure\ApiClient\ExpertSender\Chunks\WhereConditionsChunk;
use App\Infrastructure\ApiClient\ExpertSender\Request\AddUserToList;
use App\Infrastructure\ApiClient\ExpertSender\Results\ApiResult;
use App\Infrastructure\ApiClient\ExpertSender\Results\GetShortSubscribersResult;
use App\Infrastructure\ApiClient\ExpertSender\Results\ListResult;
use App\Infrastructure\ApiClient\ExpertSender\Results\TableDataResult;
use App\Infrastructure\ApiClient\ExpertSender\Results\UserIdResult;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class ExpertSender implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    protected string $apiKey;
    protected string $endpointUrl; // url without /Api
    private ?ClientInterface $client;

    public function __construct(string $endpointUrl, string $apiKey, ClientInterface $client, LoggerInterface $logger = null)
    {
        $endpointUrl = rtrim($endpointUrl, '/') . '/';
        $this->endpointUrl = $endpointUrl . 'Api/';

        $this->apiKey = $apiKey;
        $this->logger = $logger;
        $this->client = $client;
    }

    public function addUserToList(AddUserToList $request): ApiResult
    {

        // we're going to use it, so we don't want it to be changeable anymore
        // (mutable object -> value object)
        // plus it gets validated for required fields
        $request->freeze();

        $headerChunk = $this->getAddUserToListHeaderChunk($request);
        $response = $this->client->request(
            'POST',
            $this->getUrl(ExpertSenderEnum::URL_SUBSCRIBERS),
            [
                RequestOptions::HEADERS => [
                    'Content-Type' => 'text/xml',
                ],
                RequestOptions::BODY    => $headerChunk->getText(),
            ]
        );

        $apiResult = new ApiResult($response);
        $this->logApiResult(__METHOD__, $apiResult);

        return $apiResult;
    }

    public function getLists(bool $seedLists = false): ApiResult
    {
        $data = $this->getBaseData();
        if ($seedLists) {
            $data['seedLists'] = 'true';
        }

        $response = $this->client->request(
            'GET',
            $this->getUrl(ExpertSenderEnum::URL_GET_LISTS),
            [
                RequestOptions::HEADERS => [
                    'Content-Type' => 'text/xml',
                ],
                RequestOptions::QUERY   => $data,
            ]
        );

        $apiResult = new ListResult($response);
        $this->logApiResult(__METHOD__, $apiResult);

        return $apiResult;
    }

    public function deleteUser(string $email, int $listId = null): ApiResult
    {
        $data = $this->getBaseData();
        $data['email'] = $email;
        if ($listId !== null) {
            $data['listId'] = $listId;
        }

        $response = $this->client->request(
            'DELETE',
            $this->getUrl(ExpertSenderEnum::URL_SUBSCRIBERS),
            [
                RequestOptions::HEADERS => [
                    'Content-Type' => 'text/xml',
                ],
                RequestOptions::QUERY   => $data,
            ]
        );

        $apiResult = new ApiResult($response);
        $this->logApiResult(__METHOD__, $apiResult);

        return $apiResult;
    }

    public function getUserId(string $email): ApiResult
    {
        $data = $this->getBaseData();
        $data['email'] = $email;
        $data['option'] = '3';

        $response = $this->client->request(
            'GET',
            $this->getUrl(ExpertSenderEnum::URL_SUBSCRIBERS),
            [
                RequestOptions::HEADERS => [
                    'Content-Type' => 'text/xml',
                ],
                RequestOptions::QUERY   => $data,
            ]
        );

        $apiResult = new UserIdResult($response);
        $this->logApiResult(__METHOD__, $apiResult);

        return $apiResult;
    }

    public function getShortSubscriber(string $email): ApiResult
    {
        $data = $this->getBaseData();
        $data['email'] = $email;
        $data['option'] = ExpertSenderEnum::MODE_SUBSCRIBERS_SHORT;

        $response = $this->client->request(
            'GET',
            $this->getUrl(ExpertSenderEnum::URL_SUBSCRIBERS),
            [
                RequestOptions::HEADERS => [
                    'Content-Type' => 'text/xml',
                ],
                RequestOptions::QUERY   => $data,
            ]
        );

        $apiResult = new GetShortSubscribersResult($response);
        $this->logApiResult(__METHOD__, $apiResult);

        return $apiResult;
    }

    public function addTableRow(string $tableName, array $columns): ApiResult
    {
        $tableNameChunk = new SimpleChunk('TableName', $tableName);
        $dataChunk = new DataChunk();
        $columnsChunks = [];
        foreach ($columns as $column) {
            $columnsChunks[] = new ColumnChunk($column);
        }
        $columnsChunk = new ColumnsChunk($columnsChunks);
        $dataChunk->addChunk($columnsChunk);
        $groupChunk = new GroupChunk([$tableNameChunk, $dataChunk]);
        $headerChunk = $this->getHeaderChunk($groupChunk);

        $response = $this->client->request(
            'POST',
            $this->getUrl(ExpertSenderEnum::URL_ADD_TABLE_ROW),
            [
                RequestOptions::HEADERS => [
                    'Content-Type' => 'text/xml',
                ],
                RequestOptions::BODY    => $headerChunk->getText(),
            ]
        );

        $apiResult = new ApiResult($response);
        $this->logApiResult(__METHOD__, $apiResult);

        return $apiResult;
    }

    public function getTableData(
        string $tableName,
        array $columns = [],
        array $where = [],
        array $orderBy = [],
        int $limit = null
    ): ApiResult {
        $tableNameChunk = new SimpleChunk('TableName', $tableName);
        $columnsChunks = $whereChunks = $orderByChunks = [];
        foreach ($columns as $column) {
            $columnsChunks[] = new ColumnChunk($column);
        }
        foreach ($where as $condition) {
            $whereChunks[] = new WhereChunk($condition);
        }
        foreach ($orderBy as $direction) {
            $orderByChunks[] = new OrderByChunk($direction);
        }
        $groupChunk = new GroupChunk([$tableNameChunk]);
        if ($columnsChunks) {
            $groupChunk->addChunk(new ColumnsChunk($columnsChunks));
        }
        if ($whereChunks) {
            $groupChunk->addChunk(new WhereConditionsChunk($whereChunks));
        }
        if ($orderByChunks) {
            $groupChunk->addChunk(new OrderByColumnsChunk($orderByChunks));
        }
        if ($limit) {
            $limitChunk = new SimpleChunk('Limit', $limit);
            $groupChunk->addChunk($limitChunk);
        }
        $headerChunk = $this->getHeaderChunk($groupChunk);

        $response = $this->client->request(
            'POST',
            $this->getUrl(ExpertSenderEnum::URL_GET_TABLE_DATA),
            [
                RequestOptions::HEADERS => [
                    'Content-Type' => 'text/xml',
                ],
                RequestOptions::BODY    => $headerChunk->getText(),
            ]
        );

        $apiResult = new TableDataResult($response);
        $this->logApiResult(__METHOD__, $apiResult);

        return $apiResult;
    }

    public function updateTableRow(string $tableName, array $primaryKeyColumns, array $columns): ApiResult
    {
        $tableNameChunk = new SimpleChunk('TableName', $tableName);
        $primaryKeysColumnsChunks = $columnsChunks = [];
        foreach ($primaryKeyColumns as $column) {
            $primaryKeysColumnsChunks[] = new ColumnChunk($column);
        }
        foreach ($columns as $column) {
            $columnsChunks[] = new ColumnChunk($column);
        }
        $primaryKeyColumnsChunk = new PrimaryKeyColumnsChunk($primaryKeysColumnsChunks);
        $columnsChunk = new ColumnsChunk($columnsChunks);
        $groupChunk = new GroupChunk([$tableNameChunk, $primaryKeyColumnsChunk, $columnsChunk]);
        $headerChunk = $this->getHeaderChunk($groupChunk);

        $response = $this->client->request(
            'POST',
            $this->getUrl(ExpertSenderEnum::URL_UPDATE_TABLE_ROW),
            [
                RequestOptions::HEADERS => [
                    'Content-Type' => 'text/xml',
                ],
                RequestOptions::BODY    => $headerChunk->getText(),
            ]
        );

        $apiResult = new ApiResult($response);
        $this->logApiResult(__METHOD__, $apiResult);

        return $apiResult;
    }

    public function deleteTableRow(string $tableName, array $primaryKeyColumns): ApiResult
    {
        $tableNameChunk = new SimpleChunk('TableName', $tableName);
        $primaryKeysColumnsChunks = [];
        foreach ($primaryKeyColumns as $column) {
            $primaryKeysColumnsChunks[] = new ColumnChunk($column);
        }
        $primaryKeyColumnsChunk = new PrimaryKeyColumnsChunk($primaryKeysColumnsChunks);
        $groupChunk = new GroupChunk([$tableNameChunk, $primaryKeyColumnsChunk]);
        $headerChunk = $this->getHeaderChunk($groupChunk);

        $response = $this->client->request(
            'POST',
            $this->getUrl(ExpertSenderEnum::URL_DELETE_TABLE_ROW),
            [
                RequestOptions::HEADERS => [
                    'Content-Type' => 'text/xml',
                ],
                RequestOptions::BODY    => $headerChunk->getText(),
            ]
        );

        $apiResult = new ApiResult($response);
        $this->logApiResult(__METHOD__, $apiResult);

        return $apiResult;
    }

    public function changeEmail(int $listId, string $from, string $to): ApiResult
    {
        $result = $this->getUserId($from);

        $request = (new Request\AddUserToList())
            ->setMode(ExpertSenderEnum::MODE_ADD_AND_UPDATE)
            ->setId($result->getId())
            ->setListId($listId)
            ->setEmail($to)
            ->freeze();

        $apiResult = $this->addUserToList($request);

        $this->logApiResult(__METHOD__, $apiResult);

        return $apiResult;
    }

    public function sendTrigger(int $triggerId, array $receivers): ApiResult
    {
        $receiverChunks = [];
        foreach ($receivers as $receiver) {
            $receiverChunks[] = new ReceiverChunk($receiver);
        }

        $receiversChunks = new ReceiversChunk($receiverChunks);
        $dataChunk = new DataChunk('TriggerReceivers');
        $dataChunk->addChunk($receiversChunks);
        $headerChunk = $this->getHeaderChunk($dataChunk);

        $response = $this->client->request(
            'POST',
            $this->getUrl(ExpertSenderEnum::URL_TRIGGER_PATTERN, $triggerId),
            [
                RequestOptions::HEADERS => [
                    'Content-Type' => 'text/xml',
                ],
                RequestOptions::BODY    => $headerChunk->getText(),
            ]
        );

        $apiResult = new ApiResult($response);
        $this->logApiResult(__METHOD__, $apiResult);

        return $apiResult;
    }

    protected function sendTransactionalWithUrlPattern(int $transactionId, Receiver $receiver, array $snippets, $pattern): ApiResult
    {
        $snippetChunks = [];
        foreach ($snippets as $snippet) {
            $snippetChunks[] = new SnippetChunk($snippet);
        }

        $receiverChunk = new ReceiverChunk($receiver);
        $snippetsChunks = new SnippetsChunk($snippetChunks);
        $dataChunk = new DataChunk();
        $dataChunk->addChunk($receiverChunk);
        $dataChunk->addChunk($snippetsChunks);
        $headerChunk = $this->getHeaderChunk($dataChunk);

        $response = $this->client->request(
            'POST',
            $this->getUrl($pattern, $transactionId),
            [
                RequestOptions::HEADERS => [
                    'Content-Type' => 'text/xml',
                ],
                RequestOptions::BODY    => $headerChunk->getText(),
            ]
        );

        $apiResult = new ApiResult($response);
        $this->logApiResult(__METHOD__, $apiResult);

        return $apiResult;
    }

    public function sendTransactional(int $transactionId, Receiver $receiver, array $snippets = []): ApiResult
    {
        return $this->sendTransactionalWithUrlPattern($transactionId, $receiver, $snippets, ExpertSenderEnum::URL_TRANSACTIONAL_PATTERN);
    }

    public function sendSystemTransactional(int $transactionId, Receiver $receiver, array $snippets = []): ApiResult
    {
        return $this->sendTransactionalWithUrlPattern($transactionId, $receiver, $snippets, ExpertSenderEnum::URL_SYSTEM_TRANSACTIONAL_PATTERN);
    }

    protected function getUrl(...$parameters): string
    {
        return $this->endpointUrl . sprintf(...$parameters);
    }

    protected function getAddUserToListHeaderChunk(Request\AddUserToList $request): HeaderChunk
    {
        $dataChunk = new DataChunk('Subscriber');

        $dataChunk->addChunk(new SimpleChunk('Mode', $request->getMode()));
        $dataChunk->addChunk(new SimpleChunk('Email', $request->getEmail()));
        $dataChunk->addChunk(new SimpleChunk('ListId', $request->getListId()));

        if ($request->getFirstName() !== null) {
            $dataChunk->addChunk(new SimpleChunk('Firstname', $request->getFirstName()));
        }

        if ($request->getLastName() !== null) {
            $dataChunk->addChunk(new SimpleChunk('Lastname', $request->getLastName()));
        }

        if ($request->getName() !== null) {
            $dataChunk->addChunk(new SimpleChunk('Name', $request->getName()));
        }

        if ($request->getId() !== null) {
            $dataChunk->addChunk(new SimpleChunk('Id', $request->getId()));
        }

        if ($request->getTrackingCode() !== null) {
            $dataChunk->addChunk(new SimpleChunk('TrackingCode', $request->getTrackingCode()));
        }

        if ($request->getVendor() !== null) {
            $dataChunk->addChunk(new SimpleChunk('Vendor', $request->getVendor()));
        }

        if ($request->getIp() !== null) {
            $dataChunk->addChunk(new SimpleChunk('Ip', $request->getIp()));
        }

        if ($request->getPhone() !== null) {
            $dataChunk->addChunk(new SimpleChunk('Phone', $request->getPhone()));
        }

        if ($request->getCustomSubscriberId() !== null) {
            $dataChunk->addChunk(new SimpleChunk('CustomSubscriberId', $request->getCustomSubscriberId()));
        }

        $dataChunk->addChunk(new SimpleChunk('Force', $request->getForce() ? 'true' : 'false'));

        $propertiesChunks = new PropertiesChunk();

        foreach ($request->getProperties() as $property) {
            $propertiesChunks->addChunk(new PropertyChunk($property));
        }

        $dataChunk->addChunk($propertiesChunks);

        return $this->getHeaderChunk($dataChunk);
    }

    protected function getHeaderChunk(ChunkInterface $bodyChunk): HeaderChunk
    {
        return new HeaderChunk($this->apiKey, $bodyChunk);
    }

    protected function getBaseData(): array
    {
        return ['apiKey' => $this->apiKey];
    }

    protected function logApiResult(string $method, ApiResult $result): void
    {
        if ($this->logger === null) {
            return;
        }

        $level = $result->isOk() ? LogLevel::INFO : LogLevel::ERROR;
        $this->logger->log($level, sprintf('ES method "%s"', $method), (array) $result);
    }
}
