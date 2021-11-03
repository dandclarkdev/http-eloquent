<?php

namespace LaravelHttpEloquent;

class GenericModel
{
    /**
     * @var array
     */
    protected $data;

    public function __construct(...$params)
    {
        $this->data = $params;   
    }

    public function __get($property)
    {
        return $this->data[$property];
    }
}