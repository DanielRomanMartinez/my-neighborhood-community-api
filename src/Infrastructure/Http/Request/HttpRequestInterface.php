<?php

namespace App\Infrastructure\Http\Request;

use Symfony\Component\HttpFoundation\Request;

interface HttpRequestInterface
{
    public function getRequest(): ?Request;

    public function getRequestIp(): string;

    public function getDataFromBodyContent(): array;

    public function getXMLDataFromRequestParam(string $param): array;

    public function getRouteParameters(): array;

    public function getOrigin(): ?string;
}
