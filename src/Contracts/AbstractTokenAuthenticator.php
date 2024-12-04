<?php

declare(strict_types=1);

namespace OWC\ZGW\Contracts;

use Firebase\JWT\JWT;
use OWC\ZGW\ApiCredentials;

abstract class AbstractTokenAuthenticator implements TokenAuthenticator
{
    protected ApiCredentials $credentials;

    public function __construct(ApiCredentials $credentials)
    {
        $this->credentials = $credentials;
    }

    public function getAuthString(): string
    {
        return sprintf('Bearer %s', $this->generateToken());
    }

    public function generateToken(): string
    {
        return $this->encode($this->generatePayload());
    }

    protected function generatePayload(): array
    {
        return [
            'iss' => $this->credentials->getClientId(),
            'iat' => time(),
            'client_id' => $this->credentials->getClientId(),
            'user_id' => $this->credentials->getClientId(),
            'user_representation' => $this->credentials->getClientId(),
        ];
    }

    protected function encode(array $payload): string
    {
        return JWT::encode($payload, $this->credentials->getClientSecret(), 'HS256');
    }
}
