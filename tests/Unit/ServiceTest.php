<?php

namespace Tests\Unit;

use Mockery;
use stdClass;
use HttpEloquent\Service;
use HttpEloquent\GenericModel;
use HttpEloquent\Types\BaseUrl;
use PHPUnit\Framework\TestCase;
use HttpEloquent\Types\ModelMap;
use HttpEloquent\Types\ServiceConfig;
use HttpEloquent\Interfaces\HttpClient;
use GuzzleHttp\Psr7\Response as Psr7Response;
use HttpEloquent\Types\WrapperProperty;

class ServiceTest extends TestCase
{
    /**
     * @var \HttpEloquent\Service
     */
    protected $service;

    /**
     * @var \HttpEloquent\Interfaces\HttpClient|\Mockery\Mock
     */
    protected $client;

    public function setUp(): void
    {
        parent::setUp();

        /**
         * @var \HttpEloquent\Interfaces\HttpClient|\Mockery\Mock
         */
        $this->client = Mockery::mock(HttpClient::class);

        $this->service = new Service(
            new ServiceConfig(
                new BaseUrl('https://foo.com'),
                new ModelMap([
                    'foos' => GenericModel::class,
                ]),
                new WrapperProperty('data')
            ),
            $this->client
        );
    }

    public function testServiceIsAService(): void
    {
        $this->assertInstanceOf(
            Service::class,
            $this->service
        );
    }

    public function testOneMethodSetsPropertiesCorrectly(): void
    {
        $this->service->one();

        $this->assertEquals(GenericModel::class, $this->service->getResolveTo());

        $this->assertFalse($this->service->getPlural());

        $this->assertFalse($this->service->getImmutableResolveTo());

        $this->service->one(stdClass::class);

        $this->assertTrue($this->service->getImmutableResolveTo());

        $this->assertEquals(stdClass::class, $this->service->getResolveTo());
    }

    public function testPathWithModelNotInMapSetsPropertiesCorrectly(): void
    {
        $this->service->blah();

        $this->assertEquals(GenericModel::class, $this->service->getResolveTo());
    }

    public function testManyMethodSetsPropertiesCorrectly(): void
    {
        $this->service->many();

        $this->assertEquals(GenericModel::class, $this->service->getResolveTo());

        $this->assertTrue($this->service->getPlural());

        $this->assertFalse($this->service->getImmutableResolveTo());

        $this->service->many(stdClass::class);

        $this->assertTrue($this->service->getImmutableResolveTo());

        $this->assertEquals(stdClass::class, $this->service->getResolveTo());
    }

    public function testPaginationSetsPropertiesCorrectly(): void
    {
        $this->assertEquals('', (string) $this->service->getQuery());

        $this->service->page(1);

        $this->assertEquals('page=1', (string) $this->service->getQuery());

        $this->service->perPage(10);

        $this->assertEquals('page=1&per_page=10', (string) $this->service->getQuery());
    }

    public function testCanGetFirstResult(): void
    {
        /**
         * @var \Psr\Http\Message\ResponseInterface
         */
        $response = new Psr7Response(200, [], json_encode([
            'data' => [
                ['foo' => 'bar']
            ]
        ]));

        $this->client->shouldReceive([
            'get' => $response
        ]);

        $model = $this->service->first();

        $this->assertInstanceOf(GenericModel::class, $model);

        $this->assertEquals('bar', $model->foo);
    }

    public function testCanGetFirstResultWithoutWrapper(): void
    {
        /**
         * @var \Psr\Http\Message\ResponseInterface
         */
        $response = new Psr7Response(200, [], json_encode([
            ['foo' => 'bar']
        ]));

        $this->client->shouldReceive([
            'get' => $response
        ]);

        $model = (new Service(
                new ServiceConfig(
                    new BaseUrl('https://foo.com'),
                    new ModelMap([
                        'foos' => GenericModel::class,
                    ]),
                    null
                ),
                $this->client
            ))->first();

        $this->assertInstanceOf(GenericModel::class, $model);

        $this->assertEquals('bar', $model->foo);
    }

    public function testCanGetMultipleModels(): void
    {
        /**
         * @var \Psr\Http\Message\ResponseInterface
         */
        $response = new Psr7Response(200, [], json_encode([
            'data' => [
                ['foo' => 'bar']
            ]
        ]));

        $this->client->shouldReceive([
            'get' => $response
        ]);

        $results = $this->service->foos()->get();

        $this->assertIsArray($results);
        $this->assertInstanceOf(GenericModel::class, $results[0]);
        $this->assertEquals('bar',  $results[0]->foo);
        $this->assertEquals(1, count($results));
    }

    public function testCanGetSingleModel(): void
    {
        /**
         * @var \Psr\Http\Message\ResponseInterface
         */
        $response = new Psr7Response(200, [], json_encode([
            'data' => ['foo' => 'bar']
        ]));

        $this->client->shouldReceive([
            'get' => $response
        ]);

        $model = $this->service->foos(1)->get();
 
        $this->assertInstanceOf(GenericModel::class, $model);

        $this->assertEquals('bar', $model->foo);
    }

    public function testCanFindModel(): void
    {
        /**
         * @var \Psr\Http\Message\ResponseInterface
         */
        $response = new Psr7Response(200, [], json_encode([
            'data' => ['foo' => 'bar']
        ]));

        $this->client->shouldReceive([
            'get' => $response
        ]);

        $model = $this->service->foos()->find(1);

        $this->assertInstanceOf(GenericModel::class, $model);

        $this->assertEquals('bar', $model->foo);
    }

    public function testCanCreateModel(): void
    {
        /**
         * @var \Psr\Http\Message\ResponseInterface
         */
        $response = new Psr7Response(200, [], json_encode([
            'data' => ['foo' => 'bar']
        ]));

        $this->client->shouldReceive([
            'post' => $response
        ]);

        $model = $this->service->foos()->create([
            'foo' => 'bar'
        ]);

        $this->assertInstanceOf(GenericModel::class, $model);

        $this->assertEquals('bar', $model->foo);
    }

    public function testCanUpdateModel(): void
    {
        /**
         * @var \Psr\Http\Message\ResponseInterface
         */
        $response = new Psr7Response(200, [], json_encode([
            'data' => ['foo' => 'bar']
        ]));

        $this->client->shouldReceive([
            'patch' => $response
        ]);

        $model = $this->service->foos(1)->update([
            'foo' => 'bar'
        ]);

        $this->assertInstanceOf(GenericModel::class, $model);

        $this->assertEquals('bar', $model->foo);
    }

    public function testCanDeleteModel(): void
    {
        /**
         * @var \Psr\Http\Message\ResponseInterface
         */
        $response = new Psr7Response(200, [], json_encode([
            'data' => ['foo' => 'bar']
        ]));

        $this->client->shouldReceive([
            'delete' => $response
        ]);

        $model = $this->service->foos(1)->delete();

        $this->assertInstanceOf(GenericModel::class, $model);

        $this->assertEquals('bar', $model->foo);
    }

    public function testCanGetModelWithMagicMethod(): void
    {
        /**
         * @var \Psr\Http\Message\ResponseInterface
         */
        $response = new Psr7Response(200, [], json_encode([
            'data' => [
                ['foo' => 'bar']
            ]
        ]));

        $this->client->shouldReceive([
            'get' => $response
        ]);

        $results = $this->service->foos;

        $this->assertTrue($this->service->getPlural());
        $this->assertIsArray($results);
        $this->assertInstanceOf(GenericModel::class, $results[0]);
        $this->assertEquals('bar', $results[0]->foo);
        $this->assertEquals(1, count($results));
    }
}
