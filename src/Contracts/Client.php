<?php

declare(strict_types=1);

namespace OWC\ZGW\Contracts;

use OWC\ZGW\ApiUrlCollection;
use OWC\ZGW\Endpoints\Endpoint;
use OWC\ZGW\Http\RequestClientInterface;

interface Client
{
    public function __construct(
        RequestClientInterface $client,
        TokenAuthenticator $authenticator,
        ApiUrlCollection $endpoints
    );

    /**
     * @param array<int, string> $arguments
     */
    public function __call(string $name, array $arguments): Endpoint;
    public function getRequestClient(): RequestClientInterface;
    public function getAuthenticator(): TokenAuthenticator;
    public function getApiUrlCollection(): ApiUrlCollection;
    public function getVersion(): ?string;
    public function supports(string $endpoint): bool;
    public function getEndpointUrlByType(string $type): string;
}
