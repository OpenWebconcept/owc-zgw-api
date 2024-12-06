<?php

namespace OWC\ZGW\Endpoints\Filter;

use OWC\ZGW\Entities\Zaak;

class ObjectinformatieobjectenFilter extends AbstractFilter
{
    public function byZaak(Zaak $zaak): self
    {
        return $this->add('object', $zaak->url);
    }
}
