<?php

namespace OWC\ZGW\Entities\Contracts;

interface Arrayable
{
    /** @return array<mixed> */
    public function toArray(): array;
}
