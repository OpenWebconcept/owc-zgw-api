<?php

declare(strict_types=1);

namespace OWC\ZGW\Clients\RxMission;

use OWC\ZGW\Endpoints\ZakenEndpoint;
use OWC\ZGW\Contracts\AbstractClient;
use OWC\ZGW\Endpoints\RollenEndpoint;
use OWC\ZGW\Endpoints\RoltypenEndpoint;
use OWC\ZGW\Endpoints\StatussenEndpoint;
use OWC\ZGW\Endpoints\ZaaktypenEndpoint;
use OWC\ZGW\Endpoints\ResultatenEndpoint;
use OWC\ZGW\Endpoints\StatustypenEndpoint;
use OWC\ZGW\Endpoints\CatalogussenEndpoint;
use OWC\ZGW\Endpoints\ZaakobjectenEndpoint;
use OWC\ZGW\Endpoints\EigenschappenEndpoint;
use OWC\ZGW\Endpoints\ResultaattypenEndpoint;
use OWC\ZGW\Endpoints\ObjectinformatieEndpoint;
use OWC\ZGW\Endpoints\ZaakeigenschappenEndpoint;
use OWC\ZGW\Endpoints\InformatieobjecttypenEndpoint;
use OWC\ZGW\Endpoints\ZaakinformatieobjectenEndpoint;
use OWC\ZGW\Endpoints\EnkelvoudiginformatieobjectenEndpoint;

class Client extends AbstractClient
{
    public const AVAILABLE_ENDPOINTS = [
        // Zaken API
        'zaken' => [ZakenEndpoint::class, 'zaken'],
        'statussen' => [StatussenEndpoint::class, 'zaken'],
        'rollen' => [RollenEndpoint::class, 'zaken'],
        'resultaten' => [ResultatenEndpoint::class, 'zaken'],
        'zaakeigenschappen' => [ZaakeigenschappenEndpoint::class, 'zaken'],
        'zaakinformatieobjecten' => [ZaakinformatieobjectenEndpoint::class, 'zaken'],
        'zaakobjecten' => [ZaakobjectenEndpoint::class, 'zaken'],

        /**
         * Not yet implemented
         */
        // 'zaakcontactmomenten' => Endpoint::class,
        // 'zaakverzoeken' => Endpoint::class,

        // Catalogi API
        'zaaktypen' => [ZaaktypenEndpoint::class, 'catalogi'],
        'statustypen' => [StatustypenEndpoint::class, 'catalogi'],
        'roltypen' => [RoltypenEndpoint::class, 'catalogi'],
        'catalogussen' => [CatalogussenEndpoint::class, 'catalogi'],
        'resultaattypen' => [ResultaattypenEndpoint::class, 'catalogi'],
        'informatieobjecttypen' => [InformatieobjecttypenEndpoint::class, 'catalogi'],
        'eigenschappen' => [EigenschappenEndpoint::class, 'catalogi'],

        /**
         * Not yet implemented
         */
        // 'besluittypen' => Endpoint::class,
        // 'zaaktype-informatieobjecttypen' => Endpoint::class,

        // Documenten API
        'objectinformatieobjecten' => [ObjectinformatieEndpoint::class, 'documenten'],
        'enkelvoudiginformatieobjecten' => [EnkelvoudiginformatieobjectenEndpoint::class, 'documenten'],
        /**
         * Not yet implemented
         */
        // 'gebruiksrechten' => Endpoint::class,
        // 'objectinformatieobjecten' => Endpoint::class,
        // 'bestandsdelen' => Endpoint::class,
    ];
}
