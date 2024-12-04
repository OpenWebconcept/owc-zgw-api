<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts\Related;

use OWC\ZGW\Entities\Entity;
use OWC\ZGW\Support\Collection;
use OWC\ZGW\Endpoints\Filter\RollenFilter;

class Rollen extends ResourceCollection
{
    public function resolveRelatedResourceCollection(Entity $entity): Collection
    {
        $rollenEndpoint = $entity->client()->rollen();

        $filter = new RollenFilter();

        return $rollenEndpoint->filter($filter->byZaak($entity));
    }
}
