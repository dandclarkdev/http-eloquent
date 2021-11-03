<?php

namespace LaravelHttpEloquent\Interfaces;

use LaravelHttpEloquent\Types\Path;
use LaravelHttpEloquent\Types\Query;
use LaravelHttpEloquent\Types\BaseUrl;
use LaravelHttpEloquent\Types\ModelMap;

interface Service
{
    public function one($class = null): self;
    public function many($class = null): self;
    public function page(int $page): self;
    public function perPage(int $perPage): self;
    public function first();
    public function get();
    public function create(array $params);
    public function update(array $params);
    public function delete();
    public function find($id);
    public function where($key, $value): self;
    public function getUrl(): string;
    public function getPlural(): bool;
    public function setPlural(bool $plural): self;
    public function getImmutableResolveTo(): bool;
    public function setImmutableResolveTo(bool $immutableResolveTo): self;
    public function getResolveTo(): string;
    public function setResolveTo(string $resolveTo): self;
    public function getModelMap(): ModelMap;
    public function getQuery(): Query;
    public function getPath(): Path;
    public function getBaseUrl(): BaseUrl;
    public function getClient(): HttpClient;
}