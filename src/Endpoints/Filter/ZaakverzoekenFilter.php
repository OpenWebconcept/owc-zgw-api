<?php

namespace OWC\ZGW\Endpoints\Filter;

use OWC\ZGW\Entities\Zaak;

class ZaakverzoekenFilter extends AbstractFilter
{
    /**
     * Funnily enough the OpenZaak docs define this parameter as intenger
     * but I'm pretty sure a URL is a string and not a number :)
     */
    public function byZaak(Zaak $zaak): parent
    {
        return $this->add('zaak', $zaak->url);
    }

    public function byVerzoek(string $uri): parent
    {
        return $this->add('verzoek', $uri);
    }
}
