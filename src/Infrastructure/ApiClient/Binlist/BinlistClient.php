<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiClient\Binlist;

use App\Domain\Shared\BinlistCard;
use App\Infrastructure\ApiClient\Binlist\Exception\BinlistClientException;

final class BinlistClient
{
    private string $binlistUrl;

    public function __construct(string $binlistUrl)
    {
        $this->binlistUrl = $binlistUrl;
    }

    public function getCardInfo(string $cardNumber): BinlistCard
    {
        $number = $this->cleanCardNumber($cardNumber);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->binlistUrl . $number);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);

        $response = json_decode(curl_exec($ch));

        if (($curlErrorNum = curl_errno($ch)) !== 0) {
            $message = sprintf(
                'cURL error (%s): %s',
                $curlErrorNum,
                curl_error($ch)
            );

            throw new BinlistClientException($message);
        }

        curl_close($ch);

        if (!$response || !$response->success) {
            throw new BinlistClientException($response ? $response->error : 'Error fetching card information.');
        }

        return new BinlistCard(
            (isset($response->card)) ? $response->card : null,
            (isset($response->type)) ? $response->type : null,
            (isset($response->countrycode)) ? $response->countrycode : null,
            (isset($response->bank)) ? $response->bank : null
        );
    }

    private function cleanCardNumber(string $cardNumber): string
    {
        return substr(preg_replace('/\D+/', '', $cardNumber), 0, 6);
    }
}
