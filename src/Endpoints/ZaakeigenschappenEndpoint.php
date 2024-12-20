<?php

declare(strict_types=1);

namespace OWC\ZGW\Endpoints;

use OWC\ZGW\Entities\Zaak;
use OWC\ZGW\Support\Collection;
use OWC\ZGW\Entities\Zaakeigenschap;

class ZaakeigenschappenEndpoint extends Endpoint
{
    protected string $apiType = 'zaken';
    protected string $endpoint = 'zaakeigenschappen';
    protected string $entityClass = Zaakeigenschap::class;

    public function all(): Collection
    {
        $response = $this->httpClient->get(
            $this->buildUri($this->endpoint),
            $this->buildRequestOptions()
        );

        return $this->getCollection($this->handleResponse($response));
    }

    public function get(string $identifier): ?Zaakeigenschap
    {
        $response = $this->httpClient->get(
            $this->buildUri($this->endpoint . '/' . $identifier),
            $this->buildRequestOptions()
        );

        return $this->getSingleEntity($this->handleResponse($response));
    }

    public function filter(Filter\AbstractFilter $filter): Collection
    {
        $response = $this->httpClient->get(
            $this->buildUri($this->endpoint, $filter),
            $this->buildRequestOptions()
        );

        return $this->getCollection($this->handleResponse($response));
    }

    public function create(Zaak $zaak, Zaakeigenschap $model): Zaakeigenschap
    {
        $response = $this->httpClient->post(
            $this->buildUri(sprintf('zaken/%s/%s', $zaak->uuid, $this->endpoint)),
            $model->toJson(),
            $this->buildRequestOptions()
        );

        return $this->getSingleEntity($this->handleResponse($response));
    }
}
