<?php

namespace OWC\ZGW\Endpoints\Filter;

use OWC\ZGW\Entities\Resultaattype;
use OWC\ZGW\Entities\Zaak;

class ResultatenFilter extends AbstractFilter
{
    public function byZaak(Zaak $zaak)
    {
        return $this->add('zaak', $zaak->url);
    }

    public function byResultaattype(Resultaattype $resultaattype)
    {
        return $this->add('resultaattype', $resultaattype->url);
    }
}
