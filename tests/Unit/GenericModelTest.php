<?php

namespace Tests\Unit;

use HttpEloquent\GenericModel;
use PHPUnit\Framework\TestCase;

class GenericModelTest extends TestCase
{
    public function testMagicGetterWorks(): void
    {
        $model = new GenericModel(...[
            'foo' => 'bar'
        ]);

        $this->assertEquals('bar', $model->foo);
    }
}
