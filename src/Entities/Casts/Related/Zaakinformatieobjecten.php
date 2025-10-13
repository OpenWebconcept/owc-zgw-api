<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts\Related;

use OWC\ZGW\Entities\Zaak;
use OWC\ZGW\Entities\Entity;
use InvalidArgumentException;
use OWC\ZGW\Support\Collection;
use OWC\ZGW\Entities\Zaakinformatieobject;
use OWC\ZGW\Entities\Enkelvoudiginformatieobject;
use OWC\ZGW\Endpoints\Filter\ZaakinformatieobjectenFilter;

class Zaakinformatieobjecten extends ResourceCollection
{
    public function resolveRelatedResourceCollection(Entity $entity): Collection
    {
        if (! $entity instanceof Zaak) {
            throw new InvalidArgumentException("A Zaak entity is required to resolve Rollen");
        }

        $filter = new ZaakinformatieobjectenFilter();
        $objects = $entity->client()->zaakinformatieobjecten()->filter($filter->byZaak($entity));

        return $objects->filter(function ($object) {
            if (! $object instanceof Zaakinformatieobject || ! $object->informatieobject instanceof Enkelvoudiginformatieobject) {
                return false;
            }

            return ! $object->informatieobject->vertrouwelijkheidaanduiding->isClassified()
                && $object->informatieobject->status->hasFinalStatus();
        });
    }
}
