<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts;

use OWC\ZGW\Entities\Entity;

interface CastsAttributes
{
    public function get(Entity $model, string $key, $value);
    public function set(Entity $model, string $key, $value);
    public function serialize(string $name, $value);
}
