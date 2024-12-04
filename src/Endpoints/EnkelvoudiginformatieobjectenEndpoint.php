<?php

declare(strict_types=1);

namespace OWC\ZGW\Endpoints;

use OWC\ZGW\Entities\Enkelvoudiginformatieobject;
use OWC\ZGW\Http\Response;

class EnkelvoudiginformatieobjectenEndpoint extends Endpoint
{
    protected string $apiType = 'documenten';
    protected string $endpoint = 'enkelvoudiginformatieobjecten';
    protected string $entityClass = Enkelvoudiginformatieobject::class;

    public function get(string $identifier): ?Enkelvoudiginformatieobject
    {
        $response = $this->httpClient->get(
            $this->buildUriWithExpand($this->endpoint . '/' . $identifier),
            $this->buildRequestOptions()
        );

        return $this->getSingleEntity($this->handleResponse($response));
    }

    public function create(Enkelvoudiginformatieobject $model): Enkelvoudiginformatieobject
    {
        $response = $this->httpClient->post(
            $this->buildUri($this->endpoint),
            $model->toJson(),
            $this->buildRequestOptions()
        );

        return $this->getSingleEntity($this->handleResponse($response));
    }

    /**
     * Return the binary data of a document.
     */
    public function download(string $identifier): Response
    {
        $response = $this->httpClient->get(
            $this->buildUri($this->endpoint . '/' . $identifier . '/download'),
            $this->buildRequestOptions()
        );

        return $this->handleResponse($response);
    }
}
