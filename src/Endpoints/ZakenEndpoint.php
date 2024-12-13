<?php

declare(strict_types=1);

namespace OWC\ZGW\Endpoints;

use OWC\ZGW\Entities\Zaak;
use OWC\ZGW\Http\Response;
use OWC\ZGW\Entities\Entity;
use OWC\ZGW\Support\PagedCollection;

class ZakenEndpoint extends Endpoint
{
    protected string $apiType = 'zaken';
    protected string $endpoint = 'zaken';
    protected string $entityClass = Zaak::class;

    public function all(): PagedCollection
    {
        $response = $this->httpClient->get(
            $this->buildUriWithExpand($this->endpoint),
            $this->buildRequestOptions()
        );

        return $this->getPagedCollection($this->handleResponse($response));
    }

    public function get(string $identifier): ?Zaak
    {
        $response = $this->httpClient->get(
            $this->buildUriWithExpand(sprintf('%s/%s', $this->endpoint, $identifier)),
            $this->buildRequestOptions()
        );

        return $this->getSingleEntity($this->handleResponse($response));
    }

    public function filter(Filter\AbstractFilter $filter): PagedCollection
    {
        $response = $this->httpClient->get(
            $this->buildUriWithExpand($this->endpoint, $filter),
            $this->buildRequestOptions()
        );

        return $this->getPagedCollection($this->handleResponse($response));
    }

    protected function buildEntity($data): Entity
    {
        $class = $this->entityClass;

        return new $class($data, $this->client);
    }

    public function create(Zaak $model): Zaak
    {
        /**
         * @todo add field validation
         * These fields are required on a Zaak model:
         * - bronorganisatie
         * - zaaktype (URI)
         * - verantwoordelijkeOrganisatie
         * - startdatum
         * Additionally, these rules are required to pass:
         * - zaaktype != concept
         * - laatsteBetaaldatum > NOW
         */
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
            sprintf('%s/%s', $this->endpoint, $identifier),
            $this->buildRequestOptions()
        );

        return $this->handleResponse($response);
    }
}
