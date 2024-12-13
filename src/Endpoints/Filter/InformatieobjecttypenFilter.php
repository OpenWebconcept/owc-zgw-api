<?php

namespace OWC\ZGW\Endpoints\Filter;

use OWC\ZGW\Entities\Catalogus;

class InformatieobjecttypenFilter extends AbstractFilter
{
    public function byCatalogus(Catalogus $catalogus): parent
    {
        return $this->add('catalogus', $catalogus->url);
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
