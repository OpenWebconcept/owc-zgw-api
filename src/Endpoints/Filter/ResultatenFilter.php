<?php

namespace OWC\ZGW\Endpoints\Filter;

use OWC\ZGW\Entities\Zaak;
use OWC\ZGW\Entities\Resultaattype;

class ResultatenFilter extends AbstractFilter
{
    public function byZaak(Zaak $zaak): self
    {
        return $this->add('zaak', $zaak->url);
    }

    public function byResultaattype(Resultaattype $resultaattype): self
    {
        return $this->add('resultaattype', $resultaattype->url);
    }
}
