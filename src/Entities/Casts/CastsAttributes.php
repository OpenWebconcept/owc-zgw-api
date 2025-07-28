<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts;

use OWC\ZGW\Entities\Entity;

interface CastsAttributes
{
    public function get(Entity $model, string $key, mixed $value): mixed;

    public function set(Entity $model, string $key, mixed $value): mixed;

    public function serialize(string $name, mixed $value): mixed;
}
