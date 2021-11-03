<?php

namespace HttpEloquent\Types;

class Path
{
    /**
     * @var array
     */
    protected $parts = [];

    public function addPart($part): self
    {
        array_push($this->parts, (string) $part);

        return $this;
    }

    public function __call($method, array $params): self
    {
        $this->addPart($method);

        if(count($params) > 0) {
            $this->addPart($params[0]);
        }

        return $this;
    }

    public function __toString()
    {
        return implode('/', $this->parts);
    }
}