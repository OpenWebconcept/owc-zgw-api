<?php

declare(strict_types=1);

namespace OWC\ZGW\Endpoints;

use OWC\ZGW\Support\Collection;
use OWC\ZGW\Entities\Zaakinformatieobject;

class ZaakinformatieobjectenEndpoint extends Endpoint
{
    protected string $apiType = 'zaken';
    protected string $endpoint = 'zaakinformatieobjecten';
    protected string $entityClass = Zaakinformatieobject::class;

    public function all(): Collection
    {
        $response = $this->httpClient->get(
            $this->buildUri($this->endpoint),
            $this->buildRequestOptions()
        );

        return $this->getCollection($this->handleResponse($response));
    }

    public function get(string $identifier): ?Zaakinformatieobject
    {
        $response = $this->httpClient->get(
            $this->buildUri($this->endpoint . '/' . $identifier),
            $this->buildRequestOptions()
        );

        return $this->getSingleEntity($this->handleResponse($response));
    }

    /**
     * @todo
     */
    // public function create(Zaakinformatieobject $model): Zaakinformatieobject
    // {
    //     $response = $this->httpClient->post(
    //         $this->buildUri($this->endpoint),
    //         $model->toArray(),
    //         $this->buildRequestOptions()
    //     );

    //     return $this->getSingleEntity($this->handleResponse($response));
    // }

    public function filter(Filter\AbstractFilter $filter): Collection
    {
        $response = $this->httpClient->get(
            $this->buildUri($this->endpoint, $filter),
            $this->buildRequestOptions()
        );

        return $this->getCollection($this->handleResponse($response));
    }
}
