<?php

namespace App\Infrastructure\ApiClient\ExpertSender\Results;

use Psr\Http\Message\ResponseInterface;

class ApiResult
{
    protected ResponseInterface $response;
    protected int $errorCode;
    protected int $responseCode;
    protected string $errorMessage;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        $this->responseCode = $this->response->getStatusCode();
        $this->errorCode = 0;
        $this->errorMessage = '';
        $this->buildData();
    }

    public function isOk(): bool
    {
        return
            ($this->responseCode >= 200) &&
            ($this->responseCode <= 299) &&
            (!$this->errorCode || (($this->errorCode >= 200) && ($this->errorCode <= 299)));
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function getResponseCode(): int
    {
        return $this->responseCode;
    }

    protected function buildData()
    {
        try {
            $content = $this->response->getBody()->__toString();
            if (preg_match('~<Code>(.+)</Code>~', $content, $matches)) {
                $this->errorCode = (int) $matches[1];
            }
            if (preg_match('~<Message>(.+)</Message>~', $content, $matches)) {
                $this->errorMessage = (string) $matches[1];
            }
        } catch (\RuntimeException $exception) {
            $this->errorCode = 500;
            $this->errorMessage = $exception->getMessage();
        }
    }
}
