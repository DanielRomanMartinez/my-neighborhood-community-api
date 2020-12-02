<?php

declare(strict_types=1);

namespace App\Infrastructure\Logger\Processor;

use App\Infrastructure\Http\Request\HttpRequest;
use function App\Shared\Utils\getUserIP;

final class RequestProcessor
{
    private HttpRequest $request;

    public function __construct(HttpRequest $request)
    {
        $this->request = $request;
    }

    public function __invoke(array $record)
    {
        $isCli = (php_sapi_name() === 'cli');
        $request = $this->request->getRequest();

        if ($isCli || !$request) {
            return $record;
        }

        $record['ip'] = getUserIP();

        return $record;
    }
}
