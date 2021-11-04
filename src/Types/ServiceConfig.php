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

    /**
     * @var WrapperProperty|null
     */
    protected $wrapperProperty;

    public function __construct(
        BaseUrl $baseUrl,
        ModelMap $modelMap,
        ?WrapperProperty $wrapperProperty
    ) {
        $this->baseUrl = $baseUrl;
        $this->modelMap = $modelMap;
        $this->wrapperProperty = $wrapperProperty;
    }

    public function getBaseUrl(): BaseUrl
    {
        return $this->baseUrl;
    }

    public function getModelMap(): ModelMap
    {
        return $this->modelMap;
    }

    public function getWrapperProperty(): ?WrapperProperty
    {
        return $this->wrapperProperty;
    }
}
