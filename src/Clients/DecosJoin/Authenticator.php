<?php

declare(strict_types=1);

namespace OWC\ZGW\Clients\DecosJoin;

use Firebase\JWT\JWT;
use OWC\ZGW\Contracts\AbstractTokenAuthenticator;

class Authenticator extends AbstractTokenAuthenticator
{
    protected bool $useDefaultClientSecret = true;

    public function useZrcClientSecret(): void
    {
        $this->useDefaultClientSecret = false;
    }

    public function useDefaultClientSecret(): void
    {
        $this->useDefaultClientSecret = true;
    }

    /**
     * @param array<mixed> $payload
     */
    public function encode(array $payload): string
    {
        $secret = $this->useDefaultClientSecret ?
            $this->credentials->getClientSecret() :
            $this->credentials->getClientSecretZrc();

        return JWT::encode($payload, $secret, 'HS256');
    }
}
