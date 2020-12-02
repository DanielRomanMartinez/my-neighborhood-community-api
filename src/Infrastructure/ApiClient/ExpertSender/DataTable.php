<?php

namespace App\Infrastructure\ApiClient\ExpertSender;

use App\Infrastructure\ApiClient\ExpertSender\Entities\Column;
use App\Infrastructure\ApiClient\ExpertSender\Entities\Where;
use App\Infrastructure\ApiClient\ExpertSender\Exception\ExpertSenderException;
use DateTime;

class DataTable
{
    private const DATETIME_FORMAT = 'Y-m-d H:i:s';

    private ExpertSender $client;
    private string $tableName;

    public function __construct(
        ExpertSender $client,
        string $tableName
    ) {
        $this->client = $client;
        $this->tableName = $tableName;
    }

    public function tableName(): string
    {
        return $this->tableName;
    }

    public function select(array $filter = []): array
    {
        $where = [];
        foreach ($filter as $field => $value) {
            $where[] = new Where($field, ExpertSenderEnum::OPERATOR_EQUALS, $value);
        }

        $response = $this->client->getTableData($this->tableName, [], $where);

        if (!$response->isOk()) {
            throw new ExpertSenderException($response->getErrorMessage());
        }

        return $response->getData();
    }

    public function insert(array $data): void
    {
        $columns = $this->buildDataColumns($data);

        $date = (new DateTime())->format('' . self::DATETIME_FORMAT);
        $columns[] = new Column('created_at', $date);
        $columns[] = new Column('updated_at', $date);

        $response = $this->client->addTableRow($this->tableName, $columns);

        if (!$response->isOk()) {
            throw new ExpertSenderException($response->getErrorMessage());
        }
    }

    public function update(string $id, array $data): void
    {
        $columns = $this->buildDataColumns($data);

        $date = (new DateTime())->format('' . self::DATETIME_FORMAT);
        $columns[] = new Column('updated_at', $date);

        $primaryKeyColumns = $this->buildPrimaryKeyColumns($id);

        $response = $this->client->updateTableRow($this->tableName, $primaryKeyColumns, $columns);

        if (!$response->isOk()) {
            throw new ExpertSenderException($response->getErrorMessage());
        }
    }

    public function insertOrUpdate(array $fields, array $data, string $email, bool $insert = true): void
    {
        $response = $this->client->getUserId($email);
        if (!$response->isOk()) {
            throw new ExpertSenderException($response->getErrorMessage());
        }

        $result = $this->select($fields);
        if (count($result) > 0) {
            $id = $result[0][0];
            unset($data['id']);
            $this->update($id, $data);
        } else {
            if (true === $insert) {
                $this->insert($data);
            }
        }
    }

    public function delete(int $id): void
    {
        $primaryKeyColumns = $this->buildPrimaryKeyColumns($id);
        $response = $this->client->deleteTableRow($this->tableName, $primaryKeyColumns);

        if (!$response->isOk()) {
            throw new ExpertSenderException($response->getErrorMessage());
        }
    }

    private function buildDataColumns(array $data): array
    {
        $columns = [];
        foreach ($data as $key => $value) {
            $columns[] = new Column($key, $value);
        }

        return $columns;
    }

    private function buildPrimaryKeyColumns($value): array
    {
        return [new Column('application_id', $value)];
    }
}
