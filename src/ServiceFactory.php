<?php

namespace HttpEloquent;

use HttpEloquent\Service;
use HttpEloquent\Types\BaseUrl;
use HttpEloquent\Types\ModelMap;
use HttpEloquent\Types\ServiceConfig;
use HttpEloquent\Interfaces\ConfigProvider;
use HttpEloquent\Interfaces\HttpClient;
use HttpEloquent\Interfaces\ServiceFactory as ServiceFactoryInterface;

class ServiceFactory implements ServiceFactoryInterface
{
    /**
     * @var \HttpEloquent\Interfaces\ConfigProvider
     */
    protected $configProvider;

    /**
     * @var \HttpEloquent\Interfaces\HttpClient
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
            "$serviceName"
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
