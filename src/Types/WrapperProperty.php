<?php

namespace HttpEloquent\Types;

class WrapperProperty
{
    /**
     * @var string
     */
    protected $value;

    public function __construct(string $wrapperProperty)
    {
        $this->value = $wrapperProperty;
    }

    public function __toString()
    {
        return $this->value;
    }
}