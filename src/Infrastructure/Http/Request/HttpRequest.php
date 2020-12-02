<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class HttpRequest implements HttpRequestInterface
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public static function createFromRequest(Request $request): HttpRequest
    {
        $stack = new RequestStack();
        $stack->push($request);

        return new self($stack);
    }

    public function getRequest(): ?Request
    {
        return $this->requestStack->getCurrentRequest();
    }

    public function getRequestIp(): string
    {
        return $this->getRequest()->getClientIp();
    }

    public function getDataFromBodyContent(): array
    {
        return json_decode($this->getRequest()->getContent(), true) ?? [];
    }

    public function getXMLDataFromRequestParam(string $param): array
    {
        return (array) simplexml_load_string($this->getRequest()->get($param), 'SimpleXMLElement', LIBXML_NOCDATA);
    }

    public function getRouteParameters(): array
    {
        $request = $this->getRequest();

        if (!$request) {
            return [];
        }

        return $request->attributes->get('_route_params', []);
    }

    public function getOrigin(): ?string
    {
        return $this->getRequest()->headers->get('origin');
    }

    public function getUserAgent(): ?UserAgent
    {
        return new UserAgent($this->getRequest()->headers->get('User-Agent'));
    }
}
