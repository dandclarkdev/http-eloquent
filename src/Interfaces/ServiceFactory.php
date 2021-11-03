<?php

namespace LaravelHttpEloquent\Interfaces;

use LaravelHttpEloquent\Interfaces\Service;
use LaravelHttpEloquent\Interfaces\HttpClient;
use LaravelHttpEloquent\Interfaces\ConfigProvider;

interface ServiceFactory
{
    public function make(string $serviceName): Service;
    public function getClient(): HttpClient;
    public function getConfigProvider(): ConfigProvider;
    public function __call(string $method, array $parameters): Service;
}