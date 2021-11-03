<?php

namespace HttpEloquent\Types;

use HttpEloquent\Types\BaseUrl;

class ServiceConfig
{
    /**
     * @var BaseUrl
     */
    protected $baseUrl;

    /**
     * @var ModelMap
     */
    protected $modelMap;

    public function __construct(
        BaseUrl $baseUrl,
        ModelMap $modelMap
    ) {
        $this->baseUrl = $baseUrl;
        $this->modelMap = $modelMap;
    }

    public function getBaseUrl(): BaseUrl
    {
        return $this->baseUrl;
    }

    public function getModelMap(): ModelMap
    {
        return $this->modelMap;
    }
}
