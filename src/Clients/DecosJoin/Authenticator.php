<?php

declare(strict_types=1);

namespace OWC\ZGW\Clients\DecosJoin;

use Firebase\JWT\JWT;
use OWC\ZGW\Contracts\AbstractTokenAuthenticator;

class Authenticator extends AbstractTokenAuthenticator
{
    protected bool $useDefaultClientSecret = true;

    public function __construct(ApiCredentials $credentials)
    {
        $this->credentials = $credentials;
    }

    public function useZrcClientSecret(): void
    {
        $this->useDefaultClientSecret = false;
    }

    public function useDefaultClientSecret(): void
    {
        $this->useDefaultClientSecret = true;
    }

    public function encode(array $payload): string
    {
        $secret = $this->useDefaultClientSecret ?
            $this->credentials->getClientSecret() :
            $this->credentials->getClientSecretZrc();

        return JWT::encode($payload, $secret, 'HS256');
    }
}
