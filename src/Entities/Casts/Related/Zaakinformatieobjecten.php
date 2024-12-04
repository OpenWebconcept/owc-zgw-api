<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts\Related;

use OWC\ZGW\Endpoints\Filter\ZaakinformatieobjectenFilter;
use OWC\ZGW\Entities\Enkelvoudiginformatieobject;
use OWC\ZGW\Entities\Entity;
use OWC\ZGW\Entities\Zaakinformatieobject;
use OWC\ZGW\Support\Collection;

class Zaakinformatieobjecten extends ResourceCollection
{
    public function resolveRelatedResourceCollection(Entity $entity): Collection
    {
        $statussenEndpoint = $entity->client()->zaakinformatieobjecten();
        $filter = new ZaakinformatieobjectenFilter();

        $objects = $statussenEndpoint->filter($filter->byZaak($entity));

        return $objects->filter(function ($object) {
            if (! $object instanceof Zaakinformatieobject || ! $object->informatieobject instanceof Enkelvoudiginformatieobject) {
                return false;
            }

            return ! $object->informatieobject->isClassified() && $object->informatieobject->hasFinalStatus();
        });
    }
}
