<?php

declare(strict_types=1);

namespace OWC\ZGW\Clients\OpenWave;

use RuntimeException;
use OWC\ZGW\ApiCredentials;
use OWC\ZGW\Http\RequestOptions;
use OWC\ZGW\Http\RequestClientInterface;
use OWC\ZGW\Contracts\AbstractTokenAuthenticator;

class Authenticator extends AbstractTokenAuthenticator
{
    protected const tokenTransientKey = 'owc_gravityforms_zgw_auth_token';
    protected const tokenExpirationFallback = 1800; // 30 minutes.

    public function __construct(
        protected ApiCredentials $credentials,
        protected RequestClientInterface $client,
    ) {
    }

    public function generateToken(): string
    {
        $cachedToken = get_transient(self::tokenTransientKey);

        if (is_string($cachedToken) && 0 < strlen($cachedToken)) {
            return $cachedToken;
        }

        $response = $this->client->post(
            $this->credentials->getClientTokenEndpoint(),
            $this->prepareRequestBody(),
            $this->prepareRequestOptions()
        );

        $body = $response->getParsedJson();
        $token = $body['token'] ?? '';

        if (! is_string($token) || 1 > strlen($token)) {
            throw new RuntimeException('Invalid JWT token received');
        }

        $expiresIn = (int) ($body['expires_in'] ?? 0);

        if (! $expiresIn) {
            $expiresIn = self::tokenExpirationFallback;
        }

        set_transient(self::tokenTransientKey, $token, $expiresIn);

        return $token;
    }

    private function prepareRequestBody(): array
    {
        return [
            'client_id' => $this->credentials->getClientId(),
            'client_secret' => $this->credentials->getClientSecret(),
        ];
    }

    private function prepareRequestOptions(): RequestOptions
    {
        return new RequestOptions([
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($this->credentials->getClientId() . ':' . $this->credentials->getClientSecret())
            ],
        ]);
    }
}
