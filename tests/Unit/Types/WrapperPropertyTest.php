<?php

namespace Tests\Unit\Types;

use HttpEloquent\Types\BaseUrl;
use HttpEloquent\Types\WrapperProperty;
use PHPUnit\Framework\TestCase;

class WrapperPropertyTest extends TestCase
{
    public function testCastingToStringWorks(): void
    {
        $wrapper = new WrapperProperty('data');

        $this->assertEquals('data', (string) $wrapper);
    }
}
