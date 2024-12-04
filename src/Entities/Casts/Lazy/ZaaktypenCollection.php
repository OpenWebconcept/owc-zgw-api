<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts\Lazy;

use OWC\ZGW\Contracts\Client;
use OWC\ZGW\Entities\Zaaktype as ZaaktypeEntity;

class ZaaktypenCollection extends ResourceCollection
{
    protected string $registryType = 'catalogi';

    protected function resolveResource(Client $client, string $uuid): ?ZaaktypeEntity
    {
        return $client->zaaktypen()->get($uuid);
    }
}
