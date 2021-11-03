<?php

namespace LaravelHttpEloquent\Interfaces;

interface ConfigProvider
{
    public function getConfig(string $root): array;
}