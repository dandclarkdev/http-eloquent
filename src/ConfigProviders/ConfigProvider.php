<?php

namespace HttpEloquent\ConfigProviders;

use HttpEloquent\Interfaces\ConfigProvider as ConfigProviderInterface;

class ConfigProvider implements ConfigProviderInterface
{
    public function getConfig(string $root): array
    {
        return [
            'placeholder' => [
                'base_url' => 'https://jsonplaceholder.typicode.com',
            ]
        ][$root];
    }
}