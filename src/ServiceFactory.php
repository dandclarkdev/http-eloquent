<?php

namespace LaravelHttpEloquent;

use LaravelHttpEloquent\Service;
use LaravelHttpEloquent\Types\BaseUrl;
use LaravelHttpEloquent\Types\ModelMap;
use LaravelHttpEloquent\Types\ServiceConfig;
use LaravelHttpEloquent\Interfaces\ConfigProvider;
use LaravelHttpEloquent\Interfaces\HttpClient;
use LaravelHttpEloquent\Interfaces\ServiceFactory as ServiceFactoryInterface;

class ServiceFactory implements ServiceFactoryInterface
{
    /**
     * @var \LaravelHttpEloquent\Interfaces\ConfigProvider
     */
    protected $configProvider;

    /**
     * @var \LaravelHttpEloquent\Interfaces\HttpClient
     */
    protected $client;

    public function __construct(ConfigProvider $configProvider, HttpClient $client)
    {
        $this->configProvider = $configProvider;
        $this->client = $client;
    }

    public function make(string $serviceName): Service
    {
        $config = $this->configProvider->getConfig(
            "laravelhttpeloquent.services.$serviceName"
        );

        return new Service(
            new ServiceConfig(
                new BaseUrl(
                    $config['base_url']
                ),
                new ModelMap(
                    $config['models'] ?? []
                )
            ),
            $this->getClient()
        );
    }

    public function getClient(): HttpClient
    {
        return $this->client;
    }

    public function getConfigProvider(): ConfigProvider
    {
        return $this->configProvider;
    }

    public function __call(string $method, array $parameters): Service
    {
        return $this->make($method);
    }
}
