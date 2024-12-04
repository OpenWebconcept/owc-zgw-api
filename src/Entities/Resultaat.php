<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities;

class Resultaat extends Entity
{
    protected array $casts = [
        // 'url' => SomeClass::class,
        // 'uuid' => SomeClass::class,
        'zaak' => Casts\Lazy\Zaak::class,
        'resultaattype' => Casts\Lazy\Resultaattype::class,
        // 'toelichting' => Someclass::class,
    ];
}
