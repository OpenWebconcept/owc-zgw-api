<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts\Lazy;

use OWC\ZGW\Contracts\Client;
use OWC\ZGW\Entities\Zaaktype as ZaaktypeEntity;

class Zaaktype extends AbstractResource
{
    protected string $registryType = 'catalogi';
    protected string $resourceType = ZaaktypeEntity::class;

    protected function resolveResource(Client $client, string $uuid): ?ZaaktypeEntity
    {
        return $client->zaaktypen()->get($uuid);
    }
}
