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
