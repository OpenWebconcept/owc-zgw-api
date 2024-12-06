<?php

namespace OWC\ZGW\Endpoints\Filter;

use OWC\ZGW\Entities\Catalogus;

class InformatieobjecttypenFilter extends AbstractFilter
{
    public function byCatalogus(Catalogus $catalogus): self
    {
        return $this->add('catalogus', $catalogus->url);
    }

    public function byStatusConcept(): self
    {
        return $this->add('status', 'concept');
    }

    public function byStatusDefinitief(): self
    {
        return $this->add('status', 'definitief');
    }

    public function byStatusAlles(): self
    {
        return $this->add('status', 'alles');
    }
}
