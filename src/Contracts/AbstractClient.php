<?php

declare(strict_types=1);

namespace OWC\ZGW\Contracts;

use InvalidArgumentException;
use OWC\ZGW\ApiUrlCollection;
use OWC\ZGW\Endpoints\Endpoint;
use OWC\ZGW\Http\Errors\ServerError;
use OWC\ZGW\Http\RequestClientInterface;
use OWC\ZGW\Http\Errors\ResourceNotFoundError;

abstract class AbstractClient implements Client
{
    public const AVAILABLE_ENDPOINTS = [];

    /**
     * @var array<string, Endpoint>
     */
    protected array $container = [];
    protected RequestClientInterface $client;
    protected TokenAuthenticator $authenticator;
    protected ApiUrlCollection $apiUrlCollection;

    // Does every API require token authentication? Maybe replace with interface
    public function __construct(
        RequestClientInterface $client,
        TokenAuthenticator $authenticator,
        ApiUrlCollection $endpoints
    ) {
        $this->client = $client;
        $this->authenticator = $authenticator;
        $this->apiUrlCollection = $endpoints;
    }

    /**
     * @param array<int, string> $arguments
     */
    public function __call(string $name, array $arguments): Endpoint
    {
        if (isset(static::AVAILABLE_ENDPOINTS[$name])) {
            return $this->fetchFromContainer($name);
        }

        throw new \BadMethodCallException("Unknown method {$name}");
    }

    public function getRequestClient(): RequestClientInterface
    {
        return $this->client;
    }

    public function getAuthenticator(): TokenAuthenticator
    {
        return $this->authenticator;
    }

    public function getApiUrlCollection(): ApiUrlCollection
    {
        return $this->apiUrlCollection;
    }

    public function getVersion(): ?string
    {
        return $this->apiUrlCollection->getApiVersion();
    }

    public function supports(string $endpoint): bool
    {
        return isset(static::AVAILABLE_ENDPOINTS[$endpoint]);
    }

    protected function fetchFromContainer(string $key): Endpoint
    {
        if (empty($this->container[$key])) {
            $endpoint = $this->validateEndpoint($key); // Throws exception when validation fails.

            [$class, $type] = $endpoint;

            $endpoint = new $class($this);
            $this->container[$key] = $endpoint;
        }

        return $this->container[$key];
    }

    /**
     * @return string[]
     */
    protected function validateEndpoint(string $key): array
    {
        $endpoint = static::AVAILABLE_ENDPOINTS[$key] ?? false;

        if (! $endpoint) {
            throw new ResourceNotFoundError(sprintf('Available endpoint lookup of client "%s" failed. Endpoint defined by key "%s" does not exists.', get_class($this), $key));
        }

        [$class, $type] = $endpoint;

        if (! class_exists($class)) {
            throw new ServerError(sprintf('Available endpoint lookup of client "%s" failed. Class defined by key "%s" does not exists.', get_class($this), $key));
        }

        if (empty($type)) {
            throw new ServerError(sprintf('Available endpoint lookup of client "%s" failed. Defined class "%s" does not have a endpoint type defined.', get_class($this), $class));
        }

        return $endpoint;
    }

    public function getEndpointUrlByType(string $type): string
    {
        switch ($type) {
            case 'zaken':
                return $this->apiUrlCollection->getZakenEndpoint();
            case 'catalogi':
                return $this->apiUrlCollection->getCatalogiEndpoint();
            case 'documenten':
                return $this->apiUrlCollection->getDocumentenEndpoint();
            default:
                throw new InvalidArgumentException("Unknown endpoint type {$type}");
        }
    }
}
