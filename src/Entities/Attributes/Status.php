<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Attributes;

class Status extends EnumAttribute
{
    public const VALID_MEMBERS = [
        'in_bewerking', 'ter_vaststelling', 'definitief', 'gearchiveerd'
    ];

    protected string $name = 'confidentiality level';

    public function hasFinalStatus(): bool
    {
        $finalStatusses = [
            'definitief',
            'gearchiveerd',
        ];

        return in_array($this->get(), $finalStatusses);
    }
}
