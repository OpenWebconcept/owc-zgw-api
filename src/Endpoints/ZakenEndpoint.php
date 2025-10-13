<?php

declare(strict_types=1);

namespace OWC\ZGW\Endpoints;

use OWC\ZGW\Entities\Zaak;
use OWC\ZGW\Http\Response;
use OWC\ZGW\Entities\Entity;
use OWC\ZGW\Support\Collection;
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

    protected function buildEntity($data): Entity
    {
        $class = $this->entityClass;

        return new $class($data, $this->client);
    }

    protected function getStatussenSorted(Entity $zaak): Collection
    {
        $zaakType = $zaak->zaaktype;
        $statusTypen = is_object($zaakType) ? $zaakType->statustypen : null;

        if (! $statusTypen instanceof Collection) {
            return Collection::collect([]);
        }

        return $statusTypen->sortByAttribute('volgnummer')->mapWithKeys(function ($key, $statusType) {
            /**
             * Ensures uniform usage of 'volgnummers' across different clients.
             * Set the 'volgnummer' attribute of the statustype to its position in the collection (1-based index).
             */
            $statusType->setValue('volgnummer', $key + 1);

            return $statusType;
        });
    }

    protected function handleProcessStatusses(Collection $statussen, string $statusToelichting): Collection
    {
        if ($statussen->isEmpty()) {
            return $statussen;
        }

        // Not possible to match with a status connected to a 'Zaak', set the first status as current.
        if (empty($statusToelichting)) {
            $currentVolgnummer = $statussen->first()->volgnummer();

            return $this->addProcessStatusses($statussen, $currentVolgnummer);
        }

        // Get the current status which matches with the status connected to a 'Zaak'.
        $filtered = $statussen->filter(function ($status) use ($statusToelichting) {
            return strtolower($status->statusExplanation()) === strtolower($statusToelichting);
        });

        $currentVolgnummer = $filtered->first() ? $filtered->first()->volgnummer() : null;

        if (empty($currentVolgnummer)) {
            return $statussen;
        }

        return $this->addProcessStatusses($statussen, $currentVolgnummer);
    }

    protected function addProcessStatusses(Collection $statussen, string $currentVolgnummer)
    {
        return $statussen->map(function ($status) use ($currentVolgnummer) {
            $volgnummer = (int) $status->volgnummer();
            $currentNum = (int) $currentVolgnummer;

            if ($volgnummer < $currentNum) {
                $status->setValue('processStatus', 'past');
            } elseif ($volgnummer === $currentNum) {
                $status->setValue('processStatus', 'current');
            } else {
                $status->setValue('processStatus', 'future');
            }

            return $status;
        });
    }
}
