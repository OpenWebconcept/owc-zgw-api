<?php

namespace OWC\ZGW\Endpoints\Filter;

class EigenschappenFilter extends AbstractFilter
{
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
