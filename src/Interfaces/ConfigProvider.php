<?php

namespace HttpEloquent\Interfaces;

interface ConfigProvider
{
    public function getConfig(string $root): array;
}