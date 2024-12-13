<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts;

use OWC\ZGW\Entities\Entity;

abstract class AbstractCast implements CastsAttributes
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function get(Entity $model, string $key, $value)
    {
        return $value;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function set(Entity $model, string $key, $value)
    {
        return $value;
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function serialize(string $name, $value)
    {
        return $value;
    }
}
