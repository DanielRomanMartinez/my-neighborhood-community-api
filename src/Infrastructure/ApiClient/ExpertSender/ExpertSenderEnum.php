<?php

namespace App\Infrastructure\ApiClient\ExpertSender;

final class ExpertSenderEnum
{
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'int';
    const TYPE_DATE = 'date';
    const TYPE_DATE_TIME = 'dateTime';

    const OPERATOR_EQUALS = 'Equals';
    const OPERATOR_GREATER = 'Greater';
    const OPERATOR_LOWER = 'Lower';
    const OPERATOR_LIKE = 'Like';

    const ORDER_ASCENDING = 'Ascending';
    const ORDER_DESCENDING = 'Descending';

    const MODE_ADD_AND_UPDATE = 'AddAndUpdate';
    const MODE_ADD_AND_REPLACE = 'AddAndReplace';
    const MODE_ADD_AND_IGNORE = 'AddAndIgnore';
    const MODE_IGNORE_AND_UPDATE = 'IgnoreAndUpdate';
    const MODE_IGNORE_AND_REPLACE = 'IgnoreAndReplace';

    const URL_SUBSCRIBERS = 'Subscribers';
    const URL_TRIGGER_PATTERN = 'Triggers/%s';
    const URL_TRANSACTIONAL_PATTERN = 'Transactionals/%s';
    const URL_SYSTEM_TRANSACTIONAL_PATTERN = 'SystemTransactionals/%s';
    const URL_ADD_TABLE_ROW = 'DataTablesAddRow';
    const URL_DELETE_TABLE_ROW = 'DataTablesDeleteRow';
    const URL_UPDATE_TABLE_ROW = 'DataTablesUpdateRow';
    const URL_GET_TABLE_DATA = 'DataTablesGetData';
    const URL_GET_LISTS = 'Lists';

    const MODE_SUBSCRIBERS_SHORT = 'Short';
    const MODE_SUBSCRIBERS_LONG = 'Long';
    const MODE_SUBSCRIBERS_FULL = 'Full';
    const MODE_SUBSCRIBERS_EVENTS_HISTORY = 'EventsHistory';

    const DATE_TIME_FORMAT = 'Y-m-d\TH:i:s';
    const DATE_TIME_TIMEZONE = 'Europe/Madrid';

    public static function getTypes(): array
    {
        return [self::TYPE_BOOLEAN, self::TYPE_STRING, self::TYPE_INTEGER, self::TYPE_DATE, self::TYPE_DATE_TIME];
    }

    public static function getOperators(): array
    {
        return [self::OPERATOR_EQUALS, self::OPERATOR_GREATER, self::OPERATOR_LOWER, self::OPERATOR_LIKE];
    }

    public static function getOrders(): array
    {
        return [self::ORDER_ASCENDING, self::ORDER_DESCENDING];
    }

    public static function getModes(): array
    {
        return [
            self::MODE_ADD_AND_UPDATE,
            self::MODE_ADD_AND_REPLACE,
            self::MODE_ADD_AND_IGNORE,
            self::MODE_IGNORE_AND_UPDATE,
            self::MODE_IGNORE_AND_REPLACE,
        ];
    }

    public static function getSubscribersGetModes(): array
    {
        return [
            self::MODE_SUBSCRIBERS_SHORT,
            self::MODE_SUBSCRIBERS_LONG,
            self::MODE_SUBSCRIBERS_FULL,
            self::MODE_SUBSCRIBERS_EVENTS_HISTORY,
        ];
    }
}
