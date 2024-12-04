<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities;

class Statustype extends Entity
{
    protected array $casts = [
        // 'url' => "http://example.com",
        // 'omschrijving' => "string",
        // 'omschrijvingGeneriek' => "string",
        // 'statustekst' => "string",
        'zaaktype' => Casts\Lazy\Zaaktype::class,
        // 'volgnummer' => 1,
        // 'isEindstatus' => true,
        // 'informeren' => true
    ];

    public function volgnummer(): string
    {
        $volgnummer = (string) $this->getValue('volgnummer', '');

        return ltrim($volgnummer, '0');
    }
}
