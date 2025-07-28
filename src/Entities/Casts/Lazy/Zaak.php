<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts\Lazy;

use OWC\ZGW\Contracts\Client;
use OWC\ZGW\Entities\Zaak as ZaakEntity;

class Zaak extends AbstractResource
{
    protected string $registryType = 'zaken';
    protected string $resourceType = ZaakEntity::class;

    protected function resolveResource(Client $client, string $uuid): ?ZaakEntity
    {
        return $client->zaken()->get($uuid);
    }
}
