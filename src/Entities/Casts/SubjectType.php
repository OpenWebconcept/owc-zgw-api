<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts;

use UnitEnum;
use OWC\ZGW\Entities\Entity;
use InvalidArgumentException;
use OWC\ZGW\Entities\Attributes\SubjectType as SubjectTypeAttribute;

class SubjectType extends AbstractCast
{
    public function set(Entity $model, string $key, mixed $value): ?string
    {
        if (! SubjectTypeAttribute::tryFrom($value)) {
            throw new InvalidArgumentException("Invalid subject type for {$key} given");
        }

        return $value;
    }

    public function get(Entity $model, string $key, mixed $value): ?SubjectTypeAttribute
    {
        return is_string($value) ? SubjectTypeAttribute::from($value) : null;
    }

    public function serialize(string $name, mixed $value): string
    {
        return (is_object($value) && $value instanceof UnitEnum) ? $value->value : $value;
    }
}
