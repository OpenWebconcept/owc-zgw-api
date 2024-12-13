<?php

namespace OWC\ZGW\Endpoints\Filter;

use OWC\ZGW\Entities\Zaaktype;

class RoltypenFilter extends AbstractFilter
{
    public function byZaaktype(Zaaktype $zaaktype): parent
    {
        return $this->add('zaaktype', $zaaktype->uuid);
    }

    // public function byGenericDescription(string $rolDescription)
    // {
    //     // @todo...

    //     return $this->add('omschrijvingGeneriek', $rolDescription->get());
    // }

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
