<?php

namespace OWC\ZGW\Endpoints\Filter;

use OWC\ZGW\Entities\Catalogus;

class ZaaktypenFilter extends AbstractFilter
{
    public function byCatalogus(Catalogus $catalogus): self
    {
        return $this->add('catalogus', $catalogus->url);
    }

    // Does not seem to work?
    public function byKeywords(array $keywords): self
    {
        return $this->add('trefwoorden', array_filter($keywords, 'is_string'));
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
