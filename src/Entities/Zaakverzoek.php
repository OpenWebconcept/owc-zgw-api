<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities;

/**
 * @property ?string $url
 * @property ?string $uuid
 * @property ?Zaak $zaak
 * @property ?string $verzoek URI into the Klantinteracties API, which this library does not implement. Exposed as a plain string, not a resolved relation.
 */
class Zaakverzoek extends Entity
{
    protected array $casts = [
        // 'url' => "http://example.com",
        // 'uuid' => "095be615-a8ad-4c33-8e9c-c7612fbf6c9f",
        'zaak' => Casts\Lazy\Zaak::class,
        // 'verzoek' => "http://example.com",
    ];
}
