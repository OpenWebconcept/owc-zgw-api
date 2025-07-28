<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts;

use DateTimeImmutable;
use DateTimeInterface;
use OWC\ZGW\Entities\Entity;
use InvalidArgumentException;

class NullableDate extends AbstractCast
{
    public function __construct(
        protected string $format = 'Y-m-d'
    ) {
    }

    public function set(Entity $model, string $key, mixed $value): ?string
    {
        if (is_null($value)) {
            return $value;
        }

        if (is_string($value)) {
            $value = new DateTimeImmutable($value);
        }

        if (! is_object($value)) {
            throw new InvalidArgumentException("Invalid date given");
        }

        return $value->format($this->format);
    }

    public function get(Entity $model, string $key, mixed $value): ?DateTimeImmutable
    {
        return is_string($value) ? new DateTimeImmutable($value) : null;
    }

    public function serialize(string $name, mixed $value): mixed
    {
        return is_object($value) && $value instanceof DateTimeInterface ?
            $value->format($this->format) :
            $value;
    }
}
