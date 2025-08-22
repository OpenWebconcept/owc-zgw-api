<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Attributes;

enum SubjectType: string
{
    case NATUURLIJK_PERSOON = 'natuurlijk_persoon';
    case NIET_NATUURLIJK_PERSOON = 'niet_natuurlijk_persoon';
    case VESTIGING = 'vestiging';
    case ORGANISATORISCHE_EENHEID = 'organisatorische_eenheid';
    case MEDEWERKER = 'medewerker';
}
