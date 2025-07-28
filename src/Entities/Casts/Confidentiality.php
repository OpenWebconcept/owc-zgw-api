<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts;

use UnitEnum;
use OWC\ZGW\Entities\Entity;
use InvalidArgumentException;
use OWC\ZGW\Entities\Attributes\Confidentiality as ConfidentialityAttribute;

class Confidentiality extends AbstractCast
{
    public function set(Entity $model, string $key, mixed $value): ?string
    {
        if (! ConfidentialityAttribute::tryFrom($value)) {
            throw new InvalidArgumentException("Invalid confidentiality level for {$key} given");
        }

        return $value;
    }

    public function get(Entity $model, string $key, mixed $value): ?ConfidentialityAttribute
    {
        return is_string($value) ? ConfidentialityAttribute::from($value) : null;
    }

    public function serialize(string $name, mixed $value): string
    {
        return (is_object($value) && $value instanceof UnitEnum) ? $value->value : $value;
    }
}
