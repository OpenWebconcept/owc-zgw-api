<?php

declare(strict_types=1);

namespace OWC\ZGW\Contracts;

interface TokenAuthenticator
{
    public function generateToken(): string;
    public function getAuthString(): string;
}
