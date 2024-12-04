<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts\Lazy;

use OWC\ZGW\Entities\Zaak;
use OWC\ZGW\Contracts\Client;

class ZaakCollection extends ResourceCollection
{
    protected string $registryType = 'zaken';

    protected function resolveResource(Client $client, string $uuid): ?Zaak
    {
        return $client->zaken()->get($uuid);
    }
}
