<?php

namespace Tests\Unit;

use Mockery;
use stdClass;
use PHPUnit\Framework\TestCase;
use HttpEloquent\Service;
use HttpEloquent\ServiceFactory;
use HttpEloquent\Interfaces\HttpClient;
use HttpEloquent\Interfaces\ConfigProvider;

class ServiceFactoryTest extends TestCase
{
    protected const MOCK_CONFIG = [
        'base_url' => 'https://foo.com',
        'models' => [
            'bars' => stdClass::class
        ],
        'wrapper' => 'data'
    ];

    public function testCanBeInstantiated(): void
    {
        /**
         * @var \HttpEloquent\Interfaces\ConfigProvider|\Mockery\Mock
         */
        $configProvider = Mockery::mock(ConfigProvider::class);

        $configProvider->shouldReceive([
            'getConfig' => self::MOCK_CONFIG
        ]);

        /**
         * @var \HttpEloquent\Interfaces\HttpClient|\Mockery\Mock
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
         * @var \HttpEloquent\Interfaces\ConfigProvider|\Mockery\Mock
         */
        $configProvider = Mockery::mock(ConfigProvider::class);

        $configProvider->shouldReceive([
            'getConfig' => self::MOCK_CONFIG
        ]);

        /**
         * @var \HttpEloquent\Interfaces\HttpClient|\Mockery\Mock
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
         * @var \HttpEloquent\Interfaces\ConfigProvider|\Mockery\Mock
         */
        $configProvider = Mockery::mock(ConfigProvider::class);

        $configProvider->shouldReceive([
            'getConfig' => self::MOCK_CONFIG
        ]);

        /**
         * @var \HttpEloquent\Interfaces\HttpClient|\Mockery\Mock
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
