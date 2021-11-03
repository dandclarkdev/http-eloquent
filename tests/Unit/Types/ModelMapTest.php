<?php

namespace Tests\Unit\Types;

use LaravelHttpEloquent\Types\ModelMap;
use PHPUnit\Framework\TestCase;

class ModelMapTest extends TestCase
{
    public function testCanGetEntry(): void
    {
        $map = new ModelMap([
            'foo' => 'bar'
        ]);

        $this->assertEquals('bar', $map->get('foo'));
    }

    public function testCanCheckEntryExists(): void
    {
        $map = new ModelMap([
            'foo' => 'bar'
        ]);

        $this->assertTrue($map->has('foo'));

        $this->assertFalse($map->has('afdsg'));
    }
}
