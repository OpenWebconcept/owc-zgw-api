<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts\Lazy;

use OWC\ZGW\Contracts\Client;
use OWC\ZGW\Entities\Enkelvoudiginformatieobject as EnkelvoudiginformatieobjectEntity;

class Enkelvoudiginformatieobject extends Resource
{
    protected string $registryType = 'documenten';
    protected string $resourceType = EnkelvoudiginformatieobjectEntity::class;

    protected function resolveResource(Client $client, string $uuid): ?EnkelvoudiginformatieobjectEntity
    {
        return $client->enkelvoudiginformatieobjecten()->get($uuid);
    }
}
