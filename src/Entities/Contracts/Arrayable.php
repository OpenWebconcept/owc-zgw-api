<?php

namespace OWC\ZGW\Entities\Contracts;

interface Arrayable
{
    /** @var array<mixed> */
    public function toArray(): array;
}
