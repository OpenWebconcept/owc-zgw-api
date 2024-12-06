<?php

declare(strict_types=1);

namespace OWC\ZGW\Endpoints;

use OWC\ZGW\Http\PageMeta;
use OWC\ZGW\Http\Response;
use OWC\ZGW\Entities\Entity;
use OWC\ZGW\Contracts\Client;
use OWC\ZGW\Support\Collection;
use OWC\ZGW\Http\Handlers\Stack;
use OWC\ZGW\Http\RequestOptions;
use OWC\ZGW\Support\PagedCollection;
use OWC\ZGW\Http\RequestClientInterface;
use OWC\ZGW\Contracts\TokenAuthenticator;
use OWC\ZGW\Endpoints\Traits\SupportsExpand;

abstract class Endpoint
{
    use SupportsExpand;

    protected Client $client;
    protected RequestClientInterface $httpClient;
    protected TokenAuthenticator $authenticator;

    protected Stack $responseHandlers;

    protected string $apiType;
    protected string $endpoint;
    protected string $entityClass = Entity::class;


    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->httpClient = $client->getRequestClient();
        $this->authenticator = $client->getAuthenticator();
        $this->responseHandlers = Stack::create();
    }

    protected function handleResponse(Response $response): Response
    {
        foreach ($this->responseHandlers->get() as $handler) {
            $response = $handler->handle($response);
        }

        return $response;
    }

    protected function buildRequestOptions(): RequestOptions
    {
        return new RequestOptions([
            'headers' => [
                'Authorization' => $this->authenticator->getAuthString(),
            ],
        ]);
    }

    protected function buildUri(string $uri, ?Filter\AbstractFilter $filter = null): string
    {
        $uri = sprintf(
            '%s/%s',
            rtrim($this->client->getEndpointUrlByType($this->apiType), '/'),
            ltrim($uri, '/')
        );

        if ($filter) {
            $uri = \add_query_arg($filter->getParameters(), $uri);
        }

        return $uri;
    }

    protected function buildUriWithExpand(string $uri, ?Filter\AbstractFilter $filter = null): string
    {
        $uri = $this->buildUri($uri, $filter);

        if ($this->endpointSupportsExpand() && $this->expandIsEnabled()) {
            $uri = add_query_arg([
                'expand' => implode(',', $this->getExpandableResources())
            ], $uri);
        }

        return $uri;
    }

    public function getSingleEntity(Response $response): Entity
    {
        return $this->buildEntity($response->getParsedJson());
    }

    protected function getPagedCollection(Response $response): PagedCollection
    {
        $data = $response->getParsedJson();

        return new PagedCollection(
            $this->mapEntities($data['results'] ?? []),
            PageMeta::fromResponse($response)
        );
    }

    protected function getCollection(Response $response): Collection
    {
        return new Collection(
            $this->mapEntities($response->getParsedJson())
        );
    }

    /**
     * @param array<mixed> $data
     *
     * @return array<Entity>
     */
    protected function mapEntities(array $data): array
    {
        return array_map(function ($item) {
            return $this->buildEntity($item);
        }, $data);
    }

    /**
     * @param array<mixed> $data
     */
    protected function buildEntity(array $data): Entity
    {
        $class = $this->entityClass;

        return new $class($data, $this->client);
    }
}
