<?php

namespace Tests\Unit\Types;

use LaravelHttpEloquent\Types\BaseUrl;
use PHPUnit\Framework\TestCase;

class BaseUrlTest extends TestCase
{
    public function testCastingToStringWorks(): void
    {
        $baseUrl = new BaseUrl('https://foo.com');

        $this->assertEquals('https://foo.com', (string) $baseUrl);
    }
}
