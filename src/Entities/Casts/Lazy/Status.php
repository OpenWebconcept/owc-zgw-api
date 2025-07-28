<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts\Lazy;

use OWC\ZGW\Contracts\Client;
use OWC\ZGW\Entities\Status as StatusEntity;

class Status extends AbstractResource
{
    protected string $registryType = 'zaken';
    protected string $resourceType = StatusEntity::class;

    protected function resolveResource(Client $client, string $uuid): ?StatusEntity
    {
        return $client->statussen()->get($uuid);
    }
}
