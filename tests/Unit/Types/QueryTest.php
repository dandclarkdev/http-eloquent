<?php

namespace Tests\Unit\Types;

use PHPUnit\Framework\TestCase;
use HttpEloquent\Types\Query;

class QueryTest extends TestCase
{
    public function testCanAddPart(): void
    {
        $query = new Query();

        $this->assertEquals('', (string) $query);

        $query->where('foo', 'bar');

        $this->assertEquals('foo=bar', (string) $query);

        $query->where('foo', [
            'bar' => 'baz'
        ]);

        $this->assertEquals('foo%5Bbar%5D=baz', (string) $query);
    }

    public function testCanBeConvertedToArray(): void
    {
        $query = new Query();

        $query->where('foo', [
            'bar' => 'baz'
        ]);

        $this->assertEquals([
            'foo' => [
                'bar' => 'baz'
            ]
        ], $query->toArray());
    }
}
