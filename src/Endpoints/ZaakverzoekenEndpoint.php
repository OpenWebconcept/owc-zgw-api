<?php

declare(strict_types=1);

namespace OWC\ZGW\Endpoints;

use OWC\ZGW\Http\Response;
use OWC\ZGW\Entities\Entity;
use OWC\ZGW\Entities\Zaakverzoek;
use OWC\ZGW\Support\PagedCollection;

class ZaakverzoekenEndpoint extends Endpoint
{
    protected string $apiType = 'zaken';
    protected string $endpoint = 'zaakverzoeken';
    protected string $entityClass = Zaakverzoek::class;

    public function all(): PagedCollection
    {
        $response = $this->httpClient->get(
            $this->buildUri($this->endpoint),
            $this->buildRequestOptions()
        );

        return $this->getPagedCollection($this->handleResponse($response));
    }

    public function get(string $identifier): ?Zaakverzoek
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

    public function create(Zaakverzoek $model): Zaakverzoek
    {
        $response = $this->httpClient->post(
            $this->buildUri($this->endpoint),
            $model->toJson(),
            $this->buildRequestOptions()
        );

        return $this->getSingleEntity($this->handleResponse($response));
    }

    public function delete(string $identifier): Response
    {
        $response = $this->httpClient->delete(
            $this->buildUri(sprintf('%s/%s', $this->endpoint, $identifier)),
            $this->buildRequestOptions()
        );

        return $this->handleResponse($response);
    }

    protected function buildEntity($data): Entity
    {
        $class = $this->entityClass;

        return new $class($data, $this->client);
    }
}
