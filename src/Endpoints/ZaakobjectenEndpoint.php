<?php

declare(strict_types=1);

namespace OWC\ZGW\Endpoints;

use OWC\ZGW\Entities\Zaakobject;
use OWC\ZGW\Support\PagedCollection;

class ZaakobjectenEndpoint extends Endpoint
{
    protected string $apiType = 'zaken';
    protected string $endpoint = 'zaakobjecten';
    protected string $entityClass = Zaakobject::class;

    public function all(): PagedCollection
    {
        $response = $this->httpClient->get(
            $this->buildUri($this->endpoint),
            $this->buildRequestOptions()
        );

        return $this->getPagedCollection($this->handleResponse($response));
    }

    public function get(string $identifier): ?Zaakobject
    {
        $response = $this->httpClient->get(
            $this->buildUri(sprintf('%s/%s', $this->endpoint, $identifier)),
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
