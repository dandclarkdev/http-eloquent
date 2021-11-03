<?php

namespace LaravelHttpEloquent\Types;

class ModelMap
{
    /**
     * @var array
     */
    protected $map;

    public function __construct(array $map)
    {
        $this->map = $map;
    }

    public function get($key): string
    {
        return $this->map[$key];
    }

    public function has($key): bool
    {
        return isset($this->map[$key]);
    }
}