<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiClient\CommerceGate;

final class PaymentCheckout
{
    private string $offerId;
    private string $packageId;
    private string $subscriptionId;
    private string $title;
    private string $externalId;
    private string $username;
    private string $successUrl;
    private string $failedUrl;

    public function __construct(
        string $offerId,
        string $packageId,
        string $subscriptionId,
        string $title,
        string $externalId,
        string $username,
        string $successUrl,
        string $failedUrl
    ) {
        $this->offerId = $offerId;
        $this->packageId = $packageId;
        $this->subscriptionId = $subscriptionId;
        $this->title = $title;
        $this->externalId = $externalId;
        $this->username = $username;
        $this->successUrl = $successUrl;
        $this->failedUrl = $failedUrl;
    }

    public function offerId(): string
    {
        return $this->offerId;
    }

    public function packageId(): string
    {
        return $this->packageId;
    }

    public function subscriptionId(): string
    {
        return $this->subscriptionId;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function externalId(): string
    {
        return $this->externalId;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function successUrl(): string
    {
        return $this->successUrl;
    }

    public function failedUrl(): string
    {
        return $this->failedUrl;
    }
}
