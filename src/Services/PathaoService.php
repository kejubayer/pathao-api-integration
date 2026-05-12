<?php

namespace Kejubayer\PathaoIntegration\Services;

use Kejubayer\PathaoIntegration\Contracts\PathaoInterface;
use Kejubayer\PathaoIntegration\Helpers\HttpClient;

class PathaoService implements PathaoInterface
{
    protected $baseUrl;

    protected $clientId;

    protected $clientSecret;

    protected $username;

    protected $password;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('pathao.base_url'), '/');
        $this->clientId = config('pathao.client_id');
        $this->clientSecret = config('pathao.client_secret');
        $this->username = config('pathao.username');
        $this->password = config('pathao.password');
    }

    public function getAccessToken()
    {
        return HttpClient::post($this->baseUrl . '/aladdin/api/v1/issue-token', [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'username' => $this->username,
            'password' => $this->password,
            'grant_type' => 'password',
        ]);
    }

    protected function headers()
    {
        $token = $this->getAccessToken();

        return [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . ($token['access_token'] ?? ''),
        ];
    }

    public function createOrder(array $data)
    {
        return HttpClient::post(
            $this->baseUrl . '/aladdin/api/v1/orders',
            $data,
            $this->headers()
        );
    }

    public function priceCalculation(array $data)
    {
        return HttpClient::post(
            $this->baseUrl . '/aladdin/api/v1/merchant/price-plan',
            $data,
            $this->headers()
        );
    }

    public function stores()
    {
        return HttpClient::get(
            $this->baseUrl . '/aladdin/api/v1/stores',
            $this->headers()
        );
    }

    public function cities()
    {
        return HttpClient::get(
            $this->baseUrl . '/aladdin/api/v1/city-list',
            $this->headers()
        );
    }

    public function zones($cityId)
    {
        return HttpClient::get(
            $this->baseUrl . '/aladdin/api/v1/cities/' . $cityId . '/zone-list',
            $this->headers()
        );
    }

    public function areas($zoneId)
    {
        return HttpClient::get(
            $this->baseUrl . '/aladdin/api/v1/zones/' . $zoneId . '/area-list',
            $this->headers()
        );
    }

    public function trackOrder($consignmentId)
    {
        return HttpClient::get(
            $this->baseUrl . '/aladdin/api/v1/orders/' . $consignmentId . '/info',
            $this->headers()
        );
    }

    public function cancelOrder($consignmentId)
    {
        return HttpClient::post(
            $this->baseUrl . '/aladdin/api/v1/orders/' . $consignmentId . '/cancel',
            [],
            $this->headers()
        );
    }
}
