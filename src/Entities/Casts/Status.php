<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts;

use OWC\ZGW\Entities\Entity;
use InvalidArgumentException;
use OWC\ZGW\Entities\Attributes\Status as StatusAttribute;

class Status extends AbstractCast
{
    public function set(Entity $model, string $key, mixed $value): ?string
    {
        if (! StatusAttribute::tryFrom($value)) {
            throw new InvalidArgumentException("Invalid status for {$key} given");
        }

        return $value;
    }

    public function get(Entity $model, string $key, mixed $value): ?StatusAttribute
    {
        return is_string($value) ? StatusAttribute::from($value) : null;
    }

    public function serialize(string $name, mixed $value): string
    {
        return ($value instanceof StatusAttribute) ? $value->value : $value;
    }
}
