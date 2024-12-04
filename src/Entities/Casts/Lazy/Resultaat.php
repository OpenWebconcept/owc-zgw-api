<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts\Lazy;

use OWC\ZGW\Contracts\Client;
use OWC\ZGW\Entities\Resultaat as ResultaatEntity;

class Resultaat extends Resource
{
    protected string $registryType = 'zaken';
    protected string $resourceType = ResultaatEntity::class;

    protected function resolveResource(Client $client, string $uuid): ?ResultaatEntity
    {
        return $client->resultaten()->get($uuid);
    }
}
