<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts\Lazy;

use OWC\ZGW\Contracts\Client;
use OWC\ZGW\Entities\Catalogus as CatalogusEntity;

class Catalogus extends Resource
{
    protected string $registryType = 'catalogi';
    protected string $resourceType = CatalogusEntity::class;

    protected function resolveResource(Client $client, string $uuid): ?CatalogusEntity
    {
        return $client->catalogussen()->get($uuid);
    }
}
