<?php

namespace OWC\ZGW\Endpoints\Filter;

use OWC\ZGW\Entities\Zaak;
use OWC\ZGW\Entities\Attributes\ObjectType;

class ZaakobjectenFilter extends AbstractFilter
{
    public function byZaak(Zaak $zaak): parent
    {
        return $this->add('zaak', $zaak->url);
    }

    /**
     * @todo Figure out what a 'Object' is as referenced here:
     * https://test.openzaak.nl/zaken/api/v1/schema/#operation/zaakobject_list
     */
    // public function byObject(Object $object)
    // {
    //     return $this->add('object', $object->url);
    // }

    public function byObjectType(ObjectType $objectType): parent
    {
        return $this->add('objecttype', $objectType->value);
    }
}
