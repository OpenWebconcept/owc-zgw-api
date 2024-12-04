<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Attributes;

class Confidentiality extends EnumAttribute
{
    public const VALID_MEMBERS = [
        'openbaar', 'beperkt_openbaar', 'intern', 'zaakvertrouwelijk',
        'vertrouwelijk', 'confidentieel', 'geheim', 'zeer_geheim',
    ];

    protected string $name = 'confidentiality level';

    public function isCaseConfidential(): bool
    {
        return $this->is('zaakvertrouwelijk');
    }

    public function isConfidential()
    {
        return $this->is('vertrouwelijk');
    }

    public function isClassified(): bool
    {
        $classifiedDesignations = [
            'intern',
            'confidentieel',
            'geheim',
            'zeer_geheim',
        ];

        return in_array($$this->get(), $classifiedDesignations);
    }
}
