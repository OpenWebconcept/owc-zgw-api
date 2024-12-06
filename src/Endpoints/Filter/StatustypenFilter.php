<?php

namespace OWC\ZGW\Endpoints\Filter;

use OWC\ZGW\Entities\Zaaktype;

class StatustypenFilter extends AbstractFilter
{
    public function byZaaktype(Zaaktype $zaaktype): self
    {
        return $this->add('zaaktype', $zaaktype->uuid);
    }

    public function byStatus(string $status): self
    {
        if (! in_array($status, ['alles', 'definitief', 'concept'])) {
            throw new \InvalidArgumentException("Unknown statustype status {$status}");
        }

        return $this->add('status', $status);
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
