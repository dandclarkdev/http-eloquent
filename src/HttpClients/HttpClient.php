<?php

namespace HttpEloquent\HttpClients;

use GuzzleHttp\Client;
use Illuminate\Http\Client\Response;
use HttpEloquent\Interfaces\HttpClient as HttpClientInterface;
use Illuminate\Http\Client\Factory as ClientFactory;
use Psr\Http\Message\ResponseInterface;

class HttpClient implements HttpClientInterface
{
    public function get(string $url, array $query = []): ResponseInterface
    {
        return (new Client())->get($url, [
            'query' => $query
        ]);
    }

    public function post(string $url, array $params): ResponseInterface
    {
        return (new Client())->post($url, [
            'body' => $params
        ]);
    }

    public function patch(string $url, array $params): ResponseInterface
    {
        return (new Client())->patch($url, [
            'body' => $params
        ]);
    }

    public function delete(string $url): ResponseInterface
    {
        return (new Client())->delete($url);
    }
}