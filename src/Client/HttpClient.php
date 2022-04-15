<?php

namespace Payop\Client;

class HttpClient
{
    public function __construct(
        private string $endpoint,
        private string $public_key,
        private ?array $defaultHeaders = [],
        private ?array $defaultCurlOptions = [])
    {

    }

    public function request($method, $path, array $headers = [], $array_data = null): string
    {
        $array_data["publicKey"] = $this->public_key;
        $data = json_encode($array_data);
        //curl classic realization with exception handling
        return "placeholder";
    }

    public function decodeResponse($response): array
    {
        $result = json_decode($response);
        if (array_key_exists(key: 'error', array: $result)) throw ServerError;

        return $response;
    }
}