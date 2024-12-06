<?php

namespace OWC\ZGW;

class ApiUrlCollection
{
    protected ?string $zakenEndpoint;
    protected ?string $catalogiEndpoint;
    protected ?string $documentenEndpoint;
    protected ?string $apiVersion;

    public function __construct(
        ?string $zakenEndpoint = null,
        ?string $catalogiEndpoint = null,
        ?string $documentenEndpoint = null,
        ?string $apiVersion = null
    ) {
        $this->zakenEndpoint = $zakenEndpoint;
        $this->catalogiEndpoint = $catalogiEndpoint;
        $this->documentenEndpoint = $documentenEndpoint;
        $this->apiVersion = $apiVersion;
    }

    public function setZakenEndpoint(string $uri): self
    {
        $this->zakenEndpoint = $uri;

        return $this;
    }

    public function setCatalogiEndpoint(string $uri): self
    {
        $this->catalogiEndpoint = $uri;

        return $this;
    }

    public function setDocumentenEndpoint(string $uri): self
    {
        $this->documentenEndpoint = $uri;

        return $this;
    }

    public function setApiVersion(string $version): self
    {
        $this->apiVersion = $version;

        return $this;
    }

    public function getZakenEndpoint(): ?string
    {
        return $this->zakenEndpoint;
    }

    public function getCatalogiEndpoint(): ?string
    {
        return $this->catalogiEndpoint;
    }

    public function getDocumentenEndpoint(): ?string
    {
        return $this->documentenEndpoint;
    }

    public function getApiVersion(): ?string
    {
        return $this->apiVersion;
    }

    public function get(string $register): ?string
    {
        switch ($register) {
            case 'zaken':
                return $this->getZakenEndpoint();
            case 'catalogi':
                return $this->getCatalogiEndpoint();
            case 'documenten':
                return $this->getDocumentenEndpoint();
            default:
                throw new \InvalidArgumentException("Unknown register {$register}");
        }
    }
}
