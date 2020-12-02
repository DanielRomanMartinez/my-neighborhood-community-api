<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Request;

use App\Infrastructure\Http\Request\Exception\HttpRequestValidationException;
use App\Infrastructure\Validation\AbstractValidationRules;
use App\Infrastructure\Validation\ValidatorInterface;

final class RequestValidator
{
    private HttpRequestInterface $httpRequest;
    private ValidatorInterface $validator;

    public function __construct(
        HttpRequestInterface $httpRequest,
        ValidatorInterface $validator
    ) {
        $this->httpRequest = $httpRequest;
        $this->validator = $validator;
    }

    public function validate(AbstractValidationRules $rules): void
    {
        $requestData = $this->httpRequest->getDataFromBodyContent();
        $this->validator->validate($requestData, $rules);

        if (!$this->validator->isValid()) {
            $errors = $this->validator->getErrors();

            throw new HttpRequestValidationException('Invalid request data', $errors);
        }
    }
}
