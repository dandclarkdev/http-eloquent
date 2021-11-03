<?php

namespace HttpEloquent\Types;

class BaseUrl
{
    /**
     * @var string
     */
    protected $value;

    public function __construct(string $baseUrl)
    {
        $this->value = $baseUrl;
    }

    public function __toString()
    {
        return $this->value;
    }
}