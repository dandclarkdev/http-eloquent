<?php

namespace HttpEloquent;

use GuzzleHttp\Psr7\Message;
use HttpEloquent\Types\Path;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use HttpEloquent\Types\Query;
use HttpEloquent\GenericModel;
use HttpEloquent\Interfaces\HttpClient;
use HttpEloquent\Types\BaseUrl;
use HttpEloquent\Types\ModelMap;
use HttpEloquent\Types\ServiceConfig;
use HttpEloquent\Interfaces\Service as ServiceInterface;
use Psr\Http\Message\ResponseInterface;

class Service implements ServiceInterface
{
    /**
     * @var \HttpEloquent\Types\BaseUrl
     */
    protected $baseUrl;

    /**
     * @var \HttpEloquent\Types\Path
     */
    protected $path;

    /**
     * @var \HttpEloquent\Types\Query
     */
    protected $query;

    /**
     * @var \HttpEloquent\Types\ModelMap
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
     * @var \HttpEloquent\Interfaces\HttpClient
     */
    protected $client;

    /**
     * @var string|null
     */
    protected $wrapperProperty = null;

    public function __construct(ServiceConfig $config, HttpClient $client)
    {
        $this->baseUrl = $config->getBaseUrl();
        $this->path = new Path();
        $this->query = new Query();
        $this->modelMap = $config->getModelMap();
        $this->client = $client;
        $this->wrapperProperty = $config->getWrapperProperty() ? (string) $config->getWrapperProperty() : null;
    }

    protected function access(ResponseInterface $response): array
    {
        $json = json_decode((string) $response->getBody(), true);

        return $this->wrapperProperty ? $json[$this->wrapperProperty] : $json;
    }

    protected static function transformProperty(string $property): string
    {
        return $property;
    }

    protected static function transformProperties(array $resource): array
    {   
        $result = [];

        foreach ($resource as $key => $value) {
            $assoc = [static::transformProperty($key) => $value];

            foreach ($assoc as $mapKey => $mapValue) {
                $result[$mapKey] = $mapValue;
            }
        }

        return $result;
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

    protected function resolve(ResponseInterface $response)
    {
        $class = $this->getResolveTo();

        $accessed = $this->access($response);

        if ($this->getPlural()) {
            return array_map(function (array $item) use ($class) {
                return new $class(
                    ...self::transformProperties($item)
                );
            }, $accessed);
        } else {
            return new $class(...self::transformProperties($accessed));
        }
    }

    public function first()
    {
        $this->setPlural(true);

        $results = $this->resolve(
            $this->getClient()
                ->get(
                    $this->getUrl(),
                    $this->getQuery()->toArray()
                )
        );

        return count($results) > 0 ? $results[0] : null;
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
