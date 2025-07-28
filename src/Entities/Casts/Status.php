<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts;

use OWC\ZGW\Entities\Entity;
use InvalidArgumentException;
use OWC\ZGW\Entities\Attributes\EnumAttribute;
use OWC\ZGW\Entities\Attributes\Status as StatusAttribute;

class Status extends AbstractCast
{
    public function set(Entity $model, string $key, mixed $value): ?string
    {
        if (! StatusAttribute::isValidValue($value)) {
            throw new InvalidArgumentException("Invalid status for {$key} given");
        }

        return $value;
    }

    public function get(Entity $model, string $key, mixed $value): ?StatusAttribute
    {
        return is_string($value) ? new StatusAttribute($value) : null;
    }

    public function serialize(string $name, mixed $value): string
    {
        return (is_object($value) && $value instanceof EnumAttribute) ? $value->get() : $value;
    }
}
