<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts;

use OWC\ZGW\Entities\Entity;
use InvalidArgumentException;
use OWC\ZGW\Entities\Attributes\EnumAttribute;
use OWC\ZGW\Entities\Attributes\Confidentiality as ConfidentialityAttribute;

class Confidentiality extends AbstractCast
{
    public function set(Entity $model, string $key, mixed $value): ?string
    {
        if (! ConfidentialityAttribute::isValidValue($value)) {
            throw new InvalidArgumentException("Invalid confidentiality level for {$key} given");
        }

        return $value;
    }

    public function get(Entity $model, string $key, mixed $value): ?ConfidentialityAttribute
    {
        return is_string($value) ? new ConfidentialityAttribute($value) : null;
    }

    public function serialize(string $name, mixed $value): string
    {
        return (is_object($value) && $value instanceof EnumAttribute) ? $value->get() : $value;
    }
}
