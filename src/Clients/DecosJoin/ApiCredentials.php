<?php

namespace OWC\ZGW\Clients\DecosJoin;

use OWC\ZGW\ApiCredentials as BaseApiCredentials;

class ApiCredentials extends BaseApiCredentials
{
    protected string $clientSecretZrc;

    public function getClientSecretZrc(): string
    {
        return $this->clientSecretZrc;
    }

    public function setClientSecretZrc(string $token): self
    {
        $this->clientSecretZrc = $token;

        return $this;
    }
}
