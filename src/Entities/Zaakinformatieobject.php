<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities;

class Zaakinformatieobject extends Entity
{
    protected array $casts = [
        // 'url' => "http://example.com",
        // 'uuid' => "095be615-a8ad-4c33-8e9c-c7612fbf6c9f",
        'informatieobject' => Casts\Lazy\Enkelvoudiginformatieobject::class,
        'zaak' => Casts\Lazy\Zaak::class,
        // 'aardRelatieWeergave' => "Hoort bij, omgekeerd: kent",
        // 'titel' => "string",
        // 'beschrijving' => "string",
        'registratiedatum' => Casts\NullableDateTime::class,
    ];

    /**
     * @temp
     */
    public function prepareCreateJsonArgs()
    {
        $args = [
            'informatieobject' => $this->getValue('url', ''),
            'zaak' => $this->getAttributeValue('zaak', ''),
            'titel' => $this->getValue('titel', ''),
            'beschrijving' => $this->getValue('beschrijving', ''),
        ];

        return json_encode(array_filter($args));
    }
}
