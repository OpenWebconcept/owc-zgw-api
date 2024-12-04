<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts\Lazy;

use OWC\ZGW\Contracts\Client;
use OWC\ZGW\Entities\Resultaattype as ResultaattypeEntity;

class Resultaattype extends Resource
{
    protected string $registryType = 'catalogi';
    protected string $resourceType = ResultaattypeEntity::class;

    protected function resolveResource(Client $client, string $uuid): ?ResultaattypeEntity
    {
        return $client->resultaattypen()->get($uuid);
    }
}
