<?php

namespace OWC\ZGW\Endpoints\Filter;

use OWC\ZGW\Entities\Catalogus;

class ZaaktypenFilter extends AbstractFilter
{
    public function byCatalogus(Catalogus $catalogus): parent
    {
        return $this->add('catalogus', $catalogus->url);
    }

    // Does not seem to work?
    public function byKeywords(array $keywords): parent
    {
        return $this->add('trefwoorden', array_filter($keywords, 'is_string'));
    }

    public function byStatusConcept(): parent
    {
        return $this->add('status', 'concept');
    }

    public function byStatusDefinitief(): parent
    {
        return $this->add('status', 'definitief');
    }

    public function byStatusAlles(): parent
    {
        return $this->add('status', 'alles');
    }
}
