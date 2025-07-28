<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Attributes;

use Stringable;
use InvalidArgumentException;

abstract class EnumAttribute implements Stringable
{
    public const VALID_MEMBERS = [];

    protected string $name = 'Enum';
    protected string $value;

    public function __construct(string $value)
    {
        if (! static::isValidValue($value)) {
            throw new InvalidArgumentException("Unknown {$this->name} given");
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->get();
    }

    public static function isValidValue(?string $value): bool
    {
        return in_array($value, static::VALID_MEMBERS);
    }

    public function get(): string
    {
        return $this->value;
    }

    public function is(string $value): bool
    {
        return $this->value === $value;
    }

    public function isnt(string $value): bool
    {
        return ! $this->is($value);
    }
}
