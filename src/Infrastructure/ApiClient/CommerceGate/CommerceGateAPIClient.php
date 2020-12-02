<?php

declare(strict_types=1);

namespace App\Infrastructure\ApiClient\CommerceGate;

use App\Domain\Payment\Exception\GatewayAuthenticationException;
use App\Domain\Payment\Exception\GatewayBadRequestException;
use App\Domain\Payment\Exception\GatewayRequestException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;

class CommerceGateAPIClient
{
    const LANG = 'en';
    const IN_FRAME = 'false';

    private string $tokenUrl;
    private string $checkoutUrl;
    private string $url;
    private string $user;
    private string $websiteId;
    private string $password;

    public function __construct(
        string $tokenUrl,
        string $checkoutUrl,
        string $url,
        string $user,
        string $websiteId,
        string $password
    ) {
        $this->tokenUrl = $tokenUrl;
        $this->checkoutUrl = $checkoutUrl;
        $this->user = $user;
        $this->websiteId = $websiteId;
        $this->password = $password;
        $this->url = $url;
    }

    public function buildCheckoutUrl(PaymentCheckout $checkout): string
    {
        $token = $this->getToken($checkout);
        $params = [
            'token'      => $token,
            'cid'        => $this->user,
            'wid'        => $this->websiteId,
            'title'      => $checkout->title(),
            'username'   => $checkout->username(),
            'externalId' => $checkout->externalId(),
            'packid'     => $checkout->packageId(),
            'preoffer'   => $checkout->offerId(),
            'op1'        => $checkout->subscriptionId(),
            'lang'       => self::LANG,
            'inFrame'    => self::IN_FRAME,
            'successUrl' => $checkout->successUrl(),
            'failedUrl'  => $checkout->failedUrl(),
        ];

        return $this->checkoutUrl . '?' . http_build_query($params);
    }

    public function cancelSubscriptionByFirstTransactionId(string $transactionId): void
    {
        $params = [
            'customerId'           => $this->user,
            'password'             => $this->password,
            'first_transaction_id' => $transactionId,
        ];

        $client = new Client(['base_uri' => $this->url]);
        $this->handleRequest($client, 'GET', 'cancel_subscription', ['query' => $params]);
    }

    public function refundTransactionByTransactionId(string $transactionId): void
    {
        $params = [
            'customerId'         => $this->user,
            'password'           => $this->password,
            'transactionId'      => $transactionId,
            'cancelSubscription' => false,
        ];

        $client = new Client(['base_uri' => $this->url]);
        $this->handleRequest($client, 'GET', 'refund', ['query' => $params]);
    }

    private function getToken(PaymentCheckout $checkout): string
    {
        $params = [
            'cid'         => $this->user,
            'wid'         => $this->websiteId,
            'apiPassword' => $this->password,
        ];

        $client = new Client(['base_uri' => $this->tokenUrl]);
        $response = $this->handleRequest($client, 'GET', 'get-token', ['query' => $params]);

        return $response->getBody()->getContents();
    }

    private function handleRequest(Client $client, string $method, string $url, array $options = []): ResponseInterface
    {
        try {
            return $client->request($method, $url, $options);
        } catch (ConnectException $exception) {
            throw new GatewayRequestException('Error connecting to gateway Api');
        } catch (ServerException $exception) {
            throw new GatewayRequestException('Gateway Api internal server error');
        } catch (ClientException $exception) {
            if ($exception->getResponse()->getStatusCode() === Response::HTTP_FORBIDDEN) {
                throw new GatewayAuthenticationException();
            }

            throw new GatewayBadRequestException();
        }
    }

    private function formatAmount(float $amount): string
    {
        return number_format($amount, 2, ',', '');
    }
}
