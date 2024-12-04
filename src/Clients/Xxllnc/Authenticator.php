<?php

declare(strict_types=1);

namespace OWC\ZGW\Clients\Xxllnc;

use OWC\ZGW\Contracts\AbstractTokenAuthenticator;

class Authenticator extends AbstractTokenAuthenticator
{
    public function getAuthString(): string
    {
        return $this->generateToken();
    }

    public function generateToken(): string
    {
        return $this->credentials->getClientSecret();
    }
}
