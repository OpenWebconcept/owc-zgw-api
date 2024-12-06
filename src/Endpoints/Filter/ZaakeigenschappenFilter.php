<?php

namespace OWC\ZGW\Endpoints\Filter;

use OWC\ZGW\Entities\Zaak;

class ZaakeigenschappenFilter extends AbstractFilter
{
    public function byZaak(Zaak $zaak): self
    {
        return $this->add('zaak', $zaak->url);
    }
}
