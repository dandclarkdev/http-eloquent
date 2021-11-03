<?php

namespace HttpEloquent\Interfaces;

use HttpEloquent\Interfaces\Service;
use HttpEloquent\Interfaces\HttpClient;
use HttpEloquent\Interfaces\ConfigProvider;

interface ServiceFactory
{
    public function make(string $serviceName): Service;
    public function getClient(): HttpClient;
    public function getConfigProvider(): ConfigProvider;
    public function __call(string $method, array $parameters): Service;
}