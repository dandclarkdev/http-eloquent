<?php

namespace LaravelHttpEloquent\Types;

class Query
{
    /**
     * @var array
     */
    protected $parts = [];

    public function where($key, $value): self
    {
        $this->parts[$key] = $value;

        return $this;
    }

    public function toArray(): array
    {
        return $this->parts;
    }

    public function __toString()
    {
        return http_build_query($this->parts);
    }
}