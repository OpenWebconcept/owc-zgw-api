<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts\Lazy;

use OWC\ZGW\Contracts\Client;
use OWC\ZGW\Entities\Informatieobjecttype;

class InformatieobjecttypenCollection extends ResourceCollection
{
    protected string $registryType = 'catalogi';

    protected function resolveResource(Client $client, string $uuid): ?Informatieobjecttype
    {
        return $client->informatieobjecttypen()->get($uuid);
    }
}
