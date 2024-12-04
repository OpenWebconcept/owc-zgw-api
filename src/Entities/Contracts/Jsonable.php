<?php

namespace OWC\ZGW\Entities\Contracts;

interface Jsonable
{
    public function toJson(int $flags = 0, int $depth = 512): string;
}
