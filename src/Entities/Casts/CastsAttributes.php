<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts;

use OWC\ZGW\Entities\Entity;

interface CastsAttributes
{
    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function get(Entity $model, string $key, $value);

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function set(Entity $model, string $key, $value);

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function serialize(string $name, $value);
}
