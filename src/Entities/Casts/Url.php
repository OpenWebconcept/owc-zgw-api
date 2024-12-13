<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts;

use OWC\ZGW\Entities\Entity;
use InvalidArgumentException;

class Url extends AbstractCast
{
    /**
     * @param mixed $value
     */
    public function set(Entity $model, string $key, $value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL) === false) {
            throw new InvalidArgumentException("Invalid URL given");
        }

        return $value;
    }

    /**
     * @param mixed $value
     */
    public function get(Entity $model, string $key, $value)
    {
        return $value;
    }
}
