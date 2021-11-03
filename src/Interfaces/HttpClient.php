<?php

namespace LaravelHttpEloquent\Interfaces;

use Illuminate\Http\Client\Response;

interface HttpClient
{
    public function get(string $url, array $query = []): Response;

    public function post(string $url, array $params): Response;

    public function patch(string $url, array $params): Response;

    public function delete(string $url): Response;
}