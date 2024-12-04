<?php

declare(strict_types=1);

namespace OWC\ZGW\Contracts;

use OWC\ZGW\ApiUrlCollection;
use OWC\ZGW\Http\RequestClientInterface;

interface Client
{
    public function __construct(
        RequestClientInterface $client,
        TokenAuthenticator $authenticator,
        ApiUrlCollection $endpoints
    );

    public function __call($name, $arguments);
    public function getRequestClient(): RequestClientInterface;
    public function getAuthenticator(): TokenAuthenticator;
    public function supports(string $endpoint): bool;
}
