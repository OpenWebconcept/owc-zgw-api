<?php

declare(strict_types=1);

namespace OWC\ZGW\Endpoints;

use OWC\ZGW\Entities\Objectinformatie;
use OWC\ZGW\Support\PagedCollection;

class ObjectinformatieEndpoint extends Endpoint
{
    protected string $apiType = 'documenten';
    protected string $endpoint = 'objectinformatieobjecten';
    protected string $entityClass = Objectinformatie::class;

    public function all(): PagedCollection
    {
        $response = $this->httpClient->get(
            $this->buildUriWithExpand($this->endpoint),
            $this->buildRequestOptions()
        );

        return $this->getPagedCollection($this->handleResponse($response));
    }

    public function get(string $identifier): ?Objectinformatie
    {
        $response = $this->httpClient->get(
            $this->buildUriWithExpand($this->endpoint . '/' . $identifier),
            $this->buildRequestOptions()
        );

        return $this->getSingleEntity($this->handleResponse($response));
    }

    public function filter(Filter\ObjectinformatieobjectenFilter $filter): PagedCollection
    {
        $response = $this->httpClient->get(
            $this->buildUriWithExpand($this->endpoint, $filter),
            $this->buildRequestOptions($filter)
        );

        return $this->getPagedCollection($this->handleResponse($response));
    }
}
