<?php

namespace OWC\ZGW;

class ApiCredentials
{
    protected string $clientId;
    protected string $clientSecret;

    protected string $publicCertificate;
    protected string $privateCertificate;

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function hasCertificates(): bool
    {
        return isset($this->publicCertificate) && isset($this->privateCertificate);
    }

    public function getPublicCertificate(): string
    {
        return $this->publicCertificate;
    }

    public function getPrivateCertificate(): string
    {
        return $this->privateCertificate;
    }

    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function setClientSecret(string $clientSecret): self
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    public function setPublicCertificate(string $path): self
    {
        $this->publicCertificate = $path;

        return $this;
    }

    public function setPrivateCertificate(string $path): self
    {
        $this->privateCertificate = $path;

        return $this;
    }
}
