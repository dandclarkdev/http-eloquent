<?php

namespace HttpEloquent;

use HttpEloquent\Service;
use HttpEloquent\Types\BaseUrl;
use HttpEloquent\Types\ModelMap;
use HttpEloquent\Types\ServiceConfig;
use HttpEloquent\Interfaces\HttpClient;
use HttpEloquent\Interfaces\ConfigProvider;
use HttpEloquent\Interfaces\Service as ServiceInterface;
use HttpEloquent\Interfaces\ServiceFactory as ServiceFactoryInterface;
use HttpEloquent\Types\WrapperProperty;

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

    protected const FALLBACK_SERVICE_CLASS = Service::class;

    public function __construct(ConfigProvider $configProvider, HttpClient $client)
    {
        $this->configProvider = $configProvider;
        $this->client = $client;
    }

    public function make(string $serviceName): ServiceInterface
    {
        $config = $this->configProvider->getConfig(
            $serviceName
        );

        $serviceClass = isset($config['service']) ? $config['service'] : static::FALLBACK_SERVICE_CLASS;

        return new $serviceClass(
            new ServiceConfig(
                new BaseUrl(
                    $config['base_url']
                ),
                new ModelMap(
                    $config['models'] ?? []
                ),
                isset($config['wrapper']) ? new WrapperProperty(
                    $config['wrapper']
                ) : null
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
