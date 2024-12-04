<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities;

class Eigenschap extends Entity
{
    protected array $casts = [
        'zaaktype' => Casts\Lazy\Zaaktype::class,
        'status' => Casts\Lazy\Status::class,
    ];
}
