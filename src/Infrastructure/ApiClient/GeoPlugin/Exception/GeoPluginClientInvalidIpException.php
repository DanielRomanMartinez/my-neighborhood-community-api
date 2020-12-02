<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiClient\GeoPlugin\Exception;

use Exception;

final class GeoPluginClientInvalidIpException extends Exception
{
    private string $ip;

    public function __construct(string $ip)
    {
        $this->ip = $ip;

        parent::__construct();
    }

    public function errorCode(): string
    {
        return 'invalid_ip';
    }

    public function errorMessage(): string
    {
        return sprintf('The IP <%s> is invalid.', $this->ip);
    }
}
