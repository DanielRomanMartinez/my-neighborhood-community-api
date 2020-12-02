<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiClient\GeoPlugin;

use App\Domain\Shared\GeoIpInformation;
use App\Infrastructure\ApiClient\GeoPlugin\Exception\GeoPluginClientException;
use App\Infrastructure\ApiClient\GeoPlugin\Exception\GeoPluginClientInvalidIpException;

class GeoPluginClient
{
    private string $geoPluginUrl;
    private string $environment;

    const ENVIRONMENTS_NOT_ALLOWED = ['dev'];

    public function __construct(
        string $geoPluginUrl,
        string $environment
    ) {
        $this->geoPluginUrl = $geoPluginUrl;
        $this->environment = $environment;
    }

    public function __invoke(string $ip): ?GeoIpInformation
    {
        if (in_array($this->environment, self::ENVIRONMENTS_NOT_ALLOWED)) {
            return null;
        }
        $url = $this->geoPluginUrl . '?ip=' . $ip;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);

        $response = curl_exec($ch);

        if (($curlErrorNum = curl_errno($ch)) !== 0) {
            throw new GeoPluginClientException();
        }

        curl_close($ch);

        $response = unserialize($response);

        if ($response['geoplugin_status'] < 200 || $response['geoplugin_status'] > 299) {
            throw new GeoPluginClientInvalidIpException($ip);
        }

        return new GeoIpInformation(
            $response['geoplugin_city'],
            $response['geoplugin_region'],
            $response['geoplugin_countryCode'],
            $response['geoplugin_countryName'],
        );
    }
}
