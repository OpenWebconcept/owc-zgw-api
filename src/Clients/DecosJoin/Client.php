<?php

declare(strict_types=1);

namespace OWC\ZGW\Clients\DecosJoin;

use OWC\ZGW\ApiUrlCollection;
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
    protected Authenticator $authenticator;

    public function __construct(
        RequestClientInterface $client,
        Authenticator $authenticator,
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
        $credentials = resolve('dj.credentials');

        if ('zaken' === $type || 'documenten' === $type) {
            $this->authenticator->useZrcClientSecret();
        } else {
            $this->authenticator->useDefaultClientSecret();
        }

        return $this;
    }


}
