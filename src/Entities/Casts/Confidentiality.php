<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts;

use OWC\ZGW\Entities\Entity;
use InvalidArgumentException;
use OWC\ZGW\Entities\Attributes\EnumAttribute;
use OWC\ZGW\Entities\Attributes\Confidentiality as ConfidentialityAttribute;

class Confidentiality extends AbstractCast
{
    /**
     * @param mixed $value
     */
    public function set(Entity $model, string $key, $value): ?string
    {
        if (! ConfidentialityAttribute::isValidValue($value)) {
            throw new InvalidArgumentException("Invalid confidentiality level for {$key} given");
        }

        return $value;
    }

    /**
     * @param mixed $value
     */
    public function get(Entity $model, string $key, $value): ?ConfidentialityAttribute
    {
        return is_string($value) ? new ConfidentialityAttribute($value) : null;
    }

    /**
     * @param mixed $value
     */
    public function serialize(string $name, $value): string
    {
        return (is_object($value) && $value instanceof EnumAttribute) ? $value->get() : $value;
    }
}
