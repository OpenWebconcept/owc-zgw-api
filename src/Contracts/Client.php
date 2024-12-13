<?php

declare(strict_types=1);

namespace OWC\ZGW\Contracts;

use OWC\ZGW\ApiUrlCollection;
use OWC\ZGW\Endpoints\Endpoint;
use OWC\ZGW\Http\RequestClientInterface;

/**
 * @method \OWC\ZGW\Endpoints\CatalogussenEndpoint catalogussen()
 * @method \OWC\ZGW\Endpoints\EigenschappenEndpoint eigenschappen()
 * @method \OWC\ZGW\Endpoints\EnkelvoudiginformatieobjectenEndpoint enkelvoudiginformatieobjecten()
 * @method \OWC\ZGW\Endpoints\InformatieobjecttypenEndpoint informatieobjecttypen()
 * @method \OWC\ZGW\Endpoints\ObjectinformatieEndpoint objectinformatie()
 * @method \OWC\ZGW\Endpoints\ResultaattypenEndpoint resultaattypen()
 * @method \OWC\ZGW\Endpoints\ResultatenEndpoint resultaten()
 * @method \OWC\ZGW\Endpoints\RollenEndpoint rollen()
 * @method \OWC\ZGW\Endpoints\RoltypenEndpoint roltypen()
 * @method \OWC\ZGW\Endpoints\StatussenEndpoint statussen()
 * @method \OWC\ZGW\Endpoints\StatustypenEndpoint statustypen()
 * @method \OWC\ZGW\Endpoints\ZaakeigenschappenEndpoint zaakeigenschappen()
 * @method \OWC\ZGW\Endpoints\ZaakinformatieobjectenEndpoint zaakinformatieobjecten()
 * @method \OWC\ZGW\Endpoints\ZaakobjectenEndpoint zaakobjecten()
 * @method \OWC\ZGW\Endpoints\ZaaktypenEndpoint zaaktypen()
 * @method \OWC\ZGW\Endpoints\ZakenEndpoint zaken()
 */
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
