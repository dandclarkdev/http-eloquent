<?php

namespace HttpEloquent\Interfaces;

use Psr\Http\Message\ResponseInterface;

interface HttpClient
{
    public function get(string $url, array $query = []): ResponseInterface;

    public function post(string $url, array $params): ResponseInterface;

    public function patch(string $url, array $params): ResponseInterface;

    public function delete(string $url): ResponseInterface;
}