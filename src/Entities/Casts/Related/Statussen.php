<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts\Related;

use OWC\ZGW\Entities\Zaak;
use OWC\ZGW\Entities\Entity;
use InvalidArgumentException;
use OWC\ZGW\Support\Collection;
use OWC\ZGW\Endpoints\Filter\StatussenFilter;

class Statussen extends ResourceCollection
{
    public function resolveRelatedResourceCollection(Entity $entity): Collection
    {
        if (! $entity instanceof Zaak) {
            throw new InvalidArgumentException("A Zaak entity is required to resolve Rollen");
        }

        $statussenEndpoint = $entity->client()->statussen();

        $filter = new StatussenFilter();

        return $statussenEndpoint->filter($filter->byZaak($entity));
    }
}
