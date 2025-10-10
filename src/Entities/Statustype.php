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
        // 'processStatus' => "string" // This is a custom added attribute
    ];

    public function volgnummer(): string
    {
        $volgnummer = (string) $this->getValue('volgnummer', '');

        return ltrim($volgnummer, '0');
    }

    public function isCurrent(): bool
    {
        return (string) $this->processStatus === 'current';
    }

    public function isPast(): bool
    {
        return (string) $this->processStatus === 'past';
    }

    public function isFuture(): bool
    {
        return (string) $this->processStatus === 'future';
    }
}
