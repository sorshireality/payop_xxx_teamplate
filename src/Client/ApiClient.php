<?php

namespace Payop\Client;

use Payop\Client\HttpClient;

final class ApiClient
{

    public function __construct(
        private HttpClient $httpClient
    )
    {
    }

    public function send(string $url, array $data, $method): array
    {
        $response = $this->httpClient->request(
            method: $method,
            path: $url,
            headers: [],
            array_data: $data
        );

        return $this->httpClient->decodeResponse($response);
    }

    public function createInvoice(array $data): array
    {
        return $this->send(
            url: "/invoices/create",
            data: $data,
            method: "POST");
    }
}