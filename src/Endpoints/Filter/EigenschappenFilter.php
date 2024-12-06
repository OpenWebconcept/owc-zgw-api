<?php

namespace OWC\ZGW\Endpoints\Filter;

class EigenschappenFilter extends AbstractFilter
{
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
