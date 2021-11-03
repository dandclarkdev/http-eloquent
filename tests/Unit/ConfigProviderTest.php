<?php

namespace Tests\Unit;

use HttpEloquent\ConfigProviders\ConfigProvider;
use PHPUnit\Framework\TestCase;

class ConfigProviderTest extends TestCase
{
    public function testGetConfigWorks(): void
    {
        $config = (new ConfigProvider())->getConfig('placeholder');

        $this->assertIsArray($config);
        $this->assertArrayHasKey('base_url', $config);
    }
}
