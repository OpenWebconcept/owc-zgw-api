<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts;

use InvalidArgumentException;
use OWC\ZGW\Entities\Entity;
use OWC\ZGW\Entities\Attributes\EnumAttribute;
use OWC\ZGW\Entities\Attributes\SubjectType as SubjectTypeAttribute;

class SubjectType extends AbstractCast
{
    public function set(Entity $model, string $key, $value): ?string
    {
        if (! SubjectTypeAttribute::isValidValue($value)) {
            throw new InvalidArgumentException("Invalid subject type for {$key} given");
        }

        return $value;
    }

    public function get(Entity $model, string $key, $value): ?SubjectTypeAttribute
    {
        return new SubjectTypeAttribute($value);
    }

    public function serialize(string $name, $value): string
    {
        return (is_object($value) && $value instanceof EnumAttribute) ? $value->get() : $value;
    }
}