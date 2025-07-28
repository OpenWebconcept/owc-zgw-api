<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts\Lazy;

use OWC\ZGW\Contracts\Client;
use OWC\ZGW\Entities\Roltype as RoltypeEntity;

class Roltype extends AbstractResource
{
    protected string $registryType = 'catalogi';
    protected string $resourceType = RoltypeEntity::class;

    protected function resolveResource(Client $client, string $uuid): ?RoltypeEntity
    {
        return $client->roltypen()->get($uuid);
    }
}
