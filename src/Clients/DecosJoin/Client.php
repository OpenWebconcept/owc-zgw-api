<?php

declare(strict_types=1);

namespace OWC\ZGW\Clients\DecosJoin;

use OWC\ZGW\ApiUrlCollection;
use OWC\ZGW\Contracts\TokenAuthenticator;
use OWC\ZGW\Endpoints\Endpoint;
use OWC\ZGW\Endpoints\ZakenEndpoint;
use OWC\ZGW\Contracts\AbstractClient;
use OWC\ZGW\Endpoints\RollenEndpoint;
use OWC\ZGW\Endpoints\RoltypenEndpoint;
use OWC\ZGW\Endpoints\StatussenEndpoint;
use OWC\ZGW\Endpoints\ZaaktypenEndpoint;
use OWC\ZGW\Http\RequestClientInterface;
use OWC\ZGW\Endpoints\StatustypenEndpoint;
use OWC\ZGW\Endpoints\EigenschappenEndpoint;
use OWC\ZGW\Endpoints\ObjectinformatieEndpoint;
use OWC\ZGW\Endpoints\ZaakeigenschappenEndpoint;
use OWC\ZGW\Endpoints\InformatieobjecttypenEndpoint;
use OWC\ZGW\Endpoints\ZaakinformatieobjectenEndpoint;
use OWC\ZGW\Endpoints\EnkelvoudiginformatieobjectenEndpoint;

use function OWC\ZGW\resolve;

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
class Client extends AbstractClient
{
    public const AVAILABLE_ENDPOINTS = [
        // Zaken API.
        'zaken' => [ZakenEndpoint::class, 'zaken'],
        'statussen' => [StatussenEndpoint::class, 'zaken'],
        'rollen' => [RollenEndpoint::class, 'zaken'],
        'zaakeigenschappen' => [ZaakeigenschappenEndpoint::class, 'zaken'],
        'zaakinformatieobjecten' => [ZaakinformatieobjectenEndpoint::class, 'zaken'],

        // Catalogi API.
        'zaaktypen' => [ZaaktypenEndpoint::class, 'catalogi'],
        'statustypen' => [StatustypenEndpoint::class, 'catalogi'],
        'roltypen' => [RoltypenEndpoint::class, 'catalogi'],
        'informatieobjecttypen' => [InformatieobjecttypenEndpoint::class, 'catalogi'],
        'eigenschappen' => [EigenschappenEndpoint::class, 'catalogi'],

        // Documenten API
        'objectinformatieobjecten' => [ObjectinformatieEndpoint::class, 'documenten'],
        'enkelvoudiginformatieobjecten' => [EnkelvoudiginformatieobjectenEndpoint::class, 'documenten'],

        /**
         * Not yet implemented
         */
        // 'zgw.klantcontacten' => Endpoint::class,
        // 'zgw.resultaten' => Endpoint::class,
        // 'zgw.rollen' => Endpoint::class,
        // 'zgw.zaakcontactmomenten' => Endpoint::class,
        // 'zgw.zaakinformatieobjecten' => Endpoint::class,
        // 'zgw.zaakobjecten' => Endpoint::class,
        // 'zgw.zaakverzoeken' => Endpoint::class,
    ];

    /**
     * Narrow-er definition than parent class.
     */
    protected TokenAuthenticator $authenticator;

    public function __construct(
        RequestClientInterface $client,
        TokenAuthenticator $authenticator,
        ApiUrlCollection $endpoints
    ) {
        parent::__construct($client, $authenticator, $endpoints);
    }

    protected function fetchFromContainer(string $key): Endpoint
    {
        if (! isset($this->container[$key]) || empty($this->container[$key])) {
            $endpoint = $this->validateEndpoint($key); // Throws exception when validation fails.

            [$class, $type] = $endpoint;

            // Decos requires a different client secret when accessing the ZRC.
            $this->setClientSecretByType($type);

            $endpoint = new $class($this);
            $this->container[$key] = $endpoint;
        }

        return $this->container[$key];
    }

    protected function setClientSecretByType(string $type): self
    {
        if ('zaken' === $type || 'documenten' === $type) {
            $this->authenticator->useZrcClientSecret();
        } else {
            $this->authenticator->useDefaultClientSecret();
        }

        return $this;
    }


}
