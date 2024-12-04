<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts\Lazy;

use OWC\ZGW\Contracts\Client;
use OWC\ZGW\Entities\Statustype as StatustypeEntity;

class Statustype extends Resource
{
    protected string $registryType = 'catalogi';
    protected string $resourceType = StatustypeEntity::class;

    protected function resolveResource(Client $client, string $uuid): ?StatustypeEntity
    {
        return $client->statustypen()->get($uuid);
    }
}
