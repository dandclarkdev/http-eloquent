<?php

namespace Tests\Unit;

use Mockery;
use stdClass;
use PHPUnit\Framework\TestCase;
use LaravelHttpEloquent\Service;
use LaravelHttpEloquent\ServiceFactory;
use LaravelHttpEloquent\Interfaces\HttpClient;
use LaravelHttpEloquent\Interfaces\ConfigProvider;

class ServiceFactoryTest extends TestCase
{
    protected const MOCK_CONFIG = [
        'base_url' => 'https://foo.com',
        'models' => [
            'bars' => stdClass::class
        ],
    ];

    public function testCanBeInstantiated(): void
    {
        /**
         * @var \LaravelHttpEloquent\Interfaces\ConfigProvider|\Mockery\Mock
         */
        $configProvider = Mockery::mock(ConfigProvider::class);

        $configProvider->shouldReceive([
            'getConfig' => self::MOCK_CONFIG
        ]);

        /**
         * @var \LaravelHttpEloquent\Interfaces\HttpClient|\Mockery\Mock
         */
        $client = Mockery::mock(HttpClient::class);

        $factory = new ServiceFactory(
            $configProvider,
            $client
        );

        $this->assertInstanceOf(
            Service::class,
            $factory->make('foo')
        );
    }

    public function testCanBeInstantiatedWithMagicMethod(): void
    {
        /**
         * @var \LaravelHttpEloquent\Interfaces\ConfigProvider|\Mockery\Mock
         */
        $configProvider = Mockery::mock(ConfigProvider::class);

        $configProvider->shouldReceive([
            'getConfig' => self::MOCK_CONFIG
        ]);

        /**
         * @var \LaravelHttpEloquent\Interfaces\HttpClient|\Mockery\Mock
         */
        $client = Mockery::mock(HttpClient::class);

        $factory = new ServiceFactory(
            $configProvider,
            $client
        );


        $this->assertInstanceOf(
            Service::class,
            $factory->foo()
        );

        $this->assertEquals(
            'https://foo.com/',
            $factory->foo()->getUrl()
        );
    }

    public function testCanGetConfigProvider(): void
    {
        /**
         * @var \LaravelHttpEloquent\Interfaces\ConfigProvider|\Mockery\Mock
         */
        $configProvider = Mockery::mock(ConfigProvider::class);

        $configProvider->shouldReceive([
            'getConfig' => self::MOCK_CONFIG
        ]);

        /**
         * @var \LaravelHttpEloquent\Interfaces\HttpClient|\Mockery\Mock
         */
        $client = Mockery::mock(HttpClient::class);

        $factory = new ServiceFactory(
            $configProvider,
            $client
        );

        $this->assertInstanceOf(
            ConfigProvider::class,
            $factory->getConfigProvider()
        );
    }
}
