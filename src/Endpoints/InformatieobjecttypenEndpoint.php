<?php

declare(strict_types=1);

namespace OWC\ZGW\Endpoints;

use OWC\ZGW\Endpoints\Filter\AbstractFilter;
use OWC\ZGW\Entities\Informatieobjecttype;
use OWC\ZGW\Support\PagedCollection;

class InformatieobjecttypenEndpoint extends Endpoint
{
    protected string $apiType = 'catalogi';
    protected string $endpoint = 'informatieobjecttypen';
    protected string $entityClass = Informatieobjecttype::class;

    public function all(?AbstractFilter $filter = null): PagedCollection
    {
        $response = $this->httpClient->get(
            $filter ? $this->buildUri($this->endpoint, $filter) : $this->buildUri($this->endpoint),
            $this->buildRequestOptions()
        );

        return $this->getPagedCollection($this->handleResponse($response));
    }

    public function get(string $identifier): ?Informatieobjecttype
    {
        $response = $this->httpClient->get(
            $this->buildUri($this->endpoint . '/' . $identifier),
            $this->buildRequestOptions()
        );

        return $this->getSingleEntity($this->handleResponse($response));
    }

    public function filter(Filter\InformatieobjecttypenFilter $filter): PagedCollection
    {
        $response = $this->httpClient->get(
            $this->buildUri($this->endpoint, $filter),
            $this->buildRequestOptions($filter)
        );

        return $this->getPagedCollection($this->handleResponse($response));
    }
}
