<?php

namespace Tests\Unit\Types;

use PHPUnit\Framework\TestCase;
use LaravelHttpEloquent\Types\Path;

class PathTest extends TestCase
{
    public function testCanAddPart(): void
    {
        $path = new Path();

        $this->assertEquals('', (string) $path);

        $path->addPart('foo');

        $this->assertEquals('foo', (string) $path);

        $path->addPart('bar');

        $this->assertEquals('foo/bar', (string) $path);
    }

    public function testCanAddPartWithMagicMethod(): void
    {
        $path = new Path();

        $this->assertEquals('', (string) $path);

        $path->foo();

        $this->assertEquals('foo', (string) $path);

        $path->bar('1');

        $this->assertEquals('foo/bar/1', (string) $path);
    }
}
