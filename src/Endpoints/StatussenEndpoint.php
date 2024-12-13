<?php

declare(strict_types=1);

namespace OWC\ZGW\Endpoints;

use OWC\ZGW\Entities\Status;
use OWC\ZGW\Support\PagedCollection;

class StatussenEndpoint extends Endpoint
{
    protected string $apiType = 'zaken';
    protected string $endpoint = 'statussen';
    protected string $entityClass = Status::class;

    public function all(): PagedCollection
    {
        $response = $this->httpClient->get(
            $this->buildUri($this->endpoint),
            $this->buildRequestOptions()
        );

        return $this->getPagedCollection($this->handleResponse($response));
    }

    public function get(string $identifier): ?Status
    {
        $response = $this->httpClient->get(
            $this->buildUri($this->endpoint . '/' . $identifier),
            $this->buildRequestOptions()
        );

        return $this->getSingleEntity($this->handleResponse($response));
    }

    public function filter(Filter\AbstractFilter $filter): PagedCollection
    {
        $response = $this->httpClient->get(
            $this->buildUri($this->endpoint, $filter),
            $this->buildRequestOptions()
        );

        return $this->getPagedCollection($this->handleResponse($response));
    }
}
