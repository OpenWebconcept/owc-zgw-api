<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Attributes;

enum Status: string
{
    case IN_BEWERKING = 'in_bewerking';
    case TER_VASTSTELLING = 'ter_vaststelling';
    case DEFINITIEF = 'definitief';
    case GEARCHIVEERD = 'gearchiveerd';

    public function is(Status $status): bool
    {
        return $this === $status;
    }

    public function isnt(Status $status): bool
    {
        return $this !== $status;
    }

    public function hasFinalStatus(): bool
    {
        return match ($this->value) {
            self::DEFINITIEF->value, self::GEARCHIVEERD->value => true,
            default => false,
        };
    }
}
