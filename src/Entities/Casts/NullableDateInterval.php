<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts;

use Throwable;
use DateInterval;
use OWC\ZGW\Entities\Entity;
use InvalidArgumentException;

class NullableDateInterval extends AbstractCast
{
    /**
     * @param mixed $value
     */
    public function set(Entity $model, string $key, $value): ?DateInterval
    {
        if (is_null($value) || (is_object($value) && $value instanceof Dateinterval)) {
            return $value;
        }

        try {
            return new DateInterval($value);
        } catch (Throwable $e) {
            throw new InvalidArgumentException("Invalid date interval given");
        }
    }

    /**
     * @param mixed $value
     */
    public function get(Entity $model, string $key, $value): ?DateInterval
    {
        if (is_null($value) || (is_object($value) && $value instanceof Dateinterval)) {
            return $value;
        }

        return new DateInterval($value);
    }

    /**
     * @see https://news-web.php.net/php.internals/113336
     *
     * @param mixed $value
     */
    public function serialize(string $name, $value)
    {
        return rtrim(str_replace(
            ['M0S', 'H0M', 'DT0H', 'M0D', 'P0Y', 'Y0M', 'P0M'],
            ['M', 'H', 'DT', 'M', 'P', 'Y', 'P'],
            $value->format('P%yY%mM%dDT%hH%iM%sS')
        ), 'PT');
    }
}
