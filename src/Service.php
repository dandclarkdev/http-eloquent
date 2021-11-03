<?php

namespace LaravelHttpEloquent;

use LaravelHttpEloquent\Types\Path;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use LaravelHttpEloquent\Types\Query;
use LaravelHttpEloquent\GenericModel;
use LaravelHttpEloquent\Interfaces\HttpClient;
use LaravelHttpEloquent\Types\BaseUrl;
use LaravelHttpEloquent\Types\ModelMap;
use LaravelHttpEloquent\Types\ServiceConfig;
use LaravelHttpEloquent\Interfaces\Service as ServiceInterface;

class Service implements ServiceInterface
{
    /**
     * @var \LaravelHttpEloquent\Types\BaseUrl
     */
    protected $baseUrl;

    /**
     * @var \LaravelHttpEloquent\Types\Path
     */
    protected $path;

    /**
     * @var \LaravelHttpEloquent\Types\Query
     */
    protected $query;

    /**
     * @var \LaravelHttpEloquent\Types\ModelMap
     */
    protected $modelMap;

    /**
     * @var string
     */
    protected $resolveTo = GenericModel::class;

    /**
     * @var bool
     */
    protected $immutableResolveTo = false;

    /**
     * @var bool
     */
    protected $plural = false;

    /**
     * @var \LaravelHttpEloquent\Interfaces\HttpClient
     */
    protected $client;

    public function __construct(ServiceConfig $config, HttpClient $client)
    {
        $this->baseUrl = $config->getBaseUrl();
        $this->path = new Path();
        $this->query = new Query();
        $this->modelMap = $config->getModelMap();
        $this->client = $client;
    }

    public function getClient(): HttpClient
    {
        return $this->client;
    }

    public function one($class = null): self
    {
        $this->setPlural(false);

        if ($class !== null) {
            $this->setImmutableResolveTo(true);
            $this->setResolveTo($class);
        }

        return $this;
    }

    public function many($class = null): self
    {
        $this->setPlural(true);

        if ($class !== null) {
            $this->setImmutableResolveTo(true);
            $this->setResolveTo($class);
        }

        return $this;
    }

    public function page($pageNumber): self
    {
        return $this->where('page', $pageNumber);
    }

    public function perPage($perPage): self
    {
        return $this->where('per_page', $perPage);
    }

    protected function resolve(Response $response)
    {
        $class = $this->getResolveTo();

        if ($this->getPlural()) {
            return $response->collect()
                ->map(function (array $item) use ($class) {
                    return new $class(...$item);
                });
        } else {
            return new $class(...$response->json());
        }
    }

    public function first()
    {
        $this->setPlural(true);

        return $this->resolve(
            $this->getClient()
                ->get(
                    $this->getUrl(),
                    $this->getQuery()->toArray()
                )
        )->first();
    }

    public function get()
    {
        return $this->resolve(
            $this->getClient()->get(
                $this->getUrl(),
                $this->getQuery()->toArray()
            )
        );
    }

    public function create(array $params)
    {
        $this->setPlural(false);

        return $this->resolve(
            $this->getClient()->post($this->getUrl(), $params)
        );
    }

    public function update(array $params)
    {
        $this->setPlural(false);

        return $this->resolve(
            $this->getClient()->patch($this->getUrl(), $params)
        );
    }

    public function delete()
    {
        $this->setPlural(false);

        return $this->resolve(
            $this->getClient()->delete($this->getUrl())
        );
    }

    public function find($id)
    {
        $method = (string) $id;

        $this->getPath()->$method();

        $this->setPlural(false);

        return $this->get();
    }

    public function where($key, $value): self
    {
        $this->getQuery()->where($key, $value);

        return $this;
    }

    public function getUrl(): string
    {
        return implode('/', [
            (string) $this->getBaseUrl(),
            (string) $this->getPath()
        ]);
    }

     /**
     * Get the value of plural
     */
    public function getPlural(): bool
    {
        return $this->plural;
    }

    /**
     * Set the value of plural
     */
    public function setPlural(bool $plural): self
    {
        $this->plural = $plural;

        return $this;
    }

    /**
     * Get the value of immutableResolveTo
     */
    public function getImmutableResolveTo(): bool
    {
        return $this->immutableResolveTo;
    }

    /**
     * Set the value of immutableResolveTo
     */
    public function setImmutableResolveTo(bool $immutableResolveTo): self
    {
        $this->immutableResolveTo = $immutableResolveTo;

        return $this;
    }

    /**
     * Get the value of resolveTo
     */
    public function getResolveTo(): string
    {
        return $this->resolveTo;
    }

    /**
     * Set the value of resolveTo
     */
    public function setResolveTo(string $resolveTo): self
    {
        $this->resolveTo = $resolveTo;

        return $this;
    }

    /**
     * Get the value of modelMap
     */
    public function getModelMap(): ModelMap
    {
        return $this->modelMap;
    }

    /**
     * Get the value of query
     */
    public function getQuery(): Query
    {
        return $this->query;
    }

    /**
     * Get the value of path
     */
    public function getPath(): Path
    {
        return $this->path;
    }

    /**
     * Get the value of baseUrl
     */
    public function getBaseUrl(): BaseUrl
    {
        return $this->baseUrl;
    }

    public function __get(string $property)
    {
        return $this->$property()->get();
    }

    public function __call(string $method, array $params): ServiceInterface
    {
        if (count($params) > 0) {
            $this->getPath()->$method($params[0]);
        } else {
            $this->setPlural(true);

            $this->getPath()->$method();
        }

        if (!$this->getImmutableResolveTo()) {
            if ($this->getModelMap()->has($method)) {
                $this->setResolveTo($this->getModelMap()->get($method));
            } else {
                $this->setResolveTo(GenericModel::class);
            }
        }

        return $this;
    }
}
