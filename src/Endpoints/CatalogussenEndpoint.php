<?php

declare(strict_types=1);

namespace OWC\ZGW\Endpoints;

use OWC\ZGW\Entities\Catalogus;
use OWC\ZGW\Support\PagedCollection;

class CatalogussenEndpoint extends Endpoint
{
    protected string $apiType = 'catalogi';
    protected string $endpoint = 'catalogus';
    protected string $entityClass = Catalogus::class;

    public function all(): PagedCollection
    {
        $response = $this->httpClient->get(
            $this->buildUri($this->endpoint),
            $this->buildRequestOptions()
        );

        return $this->getPagedCollection($this->handleResponse($response));
    }

    public function get(string $identifier): ?Catalogus
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
