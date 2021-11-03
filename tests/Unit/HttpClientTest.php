<?php

namespace Tests\Unit;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use HttpEloquent\ConfigProviders\ConfigProvider;
use HttpEloquent\HttpClients\HttpClient;

class HttpClientTest extends TestCase
{
    public function testCanGet(): void
    {
        $client = new HttpClient(
            new MockHandler([
                new Response(200, [], json_encode([ 'foo' => 'bar' ]))
            ])
        );

        $response = $client->get('https://foo.com');

        $this->assertArrayHasKey(
            'foo',
            json_decode((string) $response->getBody(), true)
        );
    }

    public function testCanPost(): void
    {
        $client = new HttpClient(
            new MockHandler([
                new Response(200, [], json_encode([ 'foo' => 'bar' ]))
            ])
        );

        $response = $client->post('https://foo.com', []);

        $this->assertArrayHasKey(
            'foo',
            json_decode((string) $response->getBody(), true)
        );
    }

    public function testCanPatch(): void
    {
        $client = new HttpClient(
            new MockHandler([
                new Response(200, [], json_encode([ 'foo' => 'bar' ]))
            ])
        );

        $response = $client->patch('https://foo.com', []);

        $this->assertArrayHasKey(
            'foo',
            json_decode((string) $response->getBody(), true)
        );
    }

    public function testCanDelete(): void
    {
        $client = new HttpClient(
            new MockHandler([
                new Response(200, [], json_encode([ 'foo' => 'bar' ]))
            ])
        );

        $response = $client->delete('https://foo.com');

        $this->assertArrayHasKey(
            'foo',
            json_decode((string) $response->getBody(), true)
        );
    }
}
