<?php

namespace HttpEloquent\HttpClients;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\MockHandler;
use Psr\Http\Message\ResponseInterface;
use HttpEloquent\Interfaces\HttpClient as HttpClientInterface;

class HttpClient implements HttpClientInterface
{
    /**
     * @var MockHandler|null
     */
    protected $handler;

    public function __construct(?MockHandler $handler = null)
    {
        $this->handler = $handler;
    }

    public function get(string $url, array $query = []): ResponseInterface
    {
        return $this->getClient()->get($url, [
            'query' => $query
        ]);
    }

    public function post(string $url, array $params): ResponseInterface
    {
        return $this->getClient()->post($url, [
            'json' => $params
        ]);
    }

    public function patch(string $url, array $params): ResponseInterface
    {
        return $this->getClient()->patch($url, [
            'json' => $params
        ]);
    }

    public function delete(string $url): ResponseInterface
    {
        return $this->getClient()->delete($url);
    }

    protected function getClient(): Client
    {
        return new Client([
            'handler' => HandlerStack::create($this->handler)
        ]);
    }
}