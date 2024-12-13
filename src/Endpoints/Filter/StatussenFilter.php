<?php

namespace OWC\ZGW\Endpoints\Filter;

use OWC\ZGW\Entities\Zaak;
use OWC\ZGW\Entities\Statustype;

class StatussenFilter extends AbstractFilter
{
    public function byStatusType(Statustype $type): parent
    {
        return $this->add('statustype', $type->url);
    }

    public function byZaak(Zaak $zaak): parent
    {
        return $this->add('zaak', $zaak->url);
    }
}
