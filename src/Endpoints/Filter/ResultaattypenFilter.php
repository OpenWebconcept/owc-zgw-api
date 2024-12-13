<?php

namespace OWC\ZGW\Endpoints\Filter;

use OWC\ZGW\Entities\Zaaktype;

class ResultaattypenFilter extends AbstractFilter
{
    public function byZaaktype(Zaaktype $zaaktype): parent
    {
        return $this->add('zaaktype', $zaaktype->uuid);
    }

    public function byStatus(string $status): parent
    {
        if (! in_array($status, ['alles', 'definitief', 'concept'])) {
            throw new \InvalidArgumentException("Unknown statustype status {$status}");
        }

        return $this->add('status', $status);
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
