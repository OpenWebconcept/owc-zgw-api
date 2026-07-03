<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities;

/**
 * @property ?Zaak $zaak
 * @property ?Attributes\SubjectType $betrokkeneType
 * @property ?Roltype $roltype
 * @property ?\DateTimeImmutable $registratiedatum
 * @property ?string $omschrijvingGeneriek
 * @property ?array<mixed> $betrokkeneIdentificatie
 */
class Rol extends Entity
{
    protected array $casts = [
        // 'url' => SomeClass::class,
        // 'uuid' => SomeClass::class,
        'zaak' => Casts\Lazy\Zaak::class,
        // 'betrokkene' => SomeClass::class,
        'betrokkeneType' => Casts\SubjectType::class,
        'roltype' => Casts\Lazy\Roltype::class,
        // 'omschrijving' => SomeClass::class,
        // 'omschrijvingGeneriek' => SomeClass::class,
        // 'roltoelichting' => SomeClass::class,
        'registratiedatum' => Casts\NullableDateTime::class,
        // 'indicatieMachtiging' => SomeClass::class,

        /**
         * @todo betrokkeneIdentificatie should be wrapped in Attribute class
         */
        // 'betrokkeneIdentificatie' => SomeClass::class,
    ];

    public function isInitiator(): bool
    {
        return $this->omschrijvingGeneriek === 'initiator';
    }
}
