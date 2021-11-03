<?php

namespace LaravelHttpEloquent\HttpClients;

use Illuminate\Http\Client\Response;
use LaravelHttpEloquent\Interfaces\HttpClient;
use Illuminate\Http\Client\Factory as ClientFactory;

class LaravelHttpClient implements HttpClient
{
    public function get(string $url, array $query = []): Response
    {
        return (new ClientFactory())->get($url, $query);
    }

    public function post(string $url, array $params): Response
    {
        return (new ClientFactory())->post($url, $params);
    }

    public function patch(string $url, array $params): Response
    {
        return (new ClientFactory())->patch($url, $params);
    }

    public function delete(string $url): Response
    {
        return (new ClientFactory())->delete($url);
    }
}