<?php

declare(strict_types=1);

namespace OWC\ZGW\Http;

class SslCertificatesStore
{
    protected string $publicKeyPath;
    protected string $privateKeyPath;

    public function __construct(string $publicKeyPath, string $privateKeyPath)
    {
        $this->publicKeyPath = $publicKeyPath;
        $this->privateKeyPath = $privateKeyPath;
    }

    public function getPublicCertificatePath(): string
    {
        return $this->publicKeyPath;
    }

    public function getPrivateCertificatePath(): string
    {
        return $this->privateKeyPath;
    }

    public function isEmpty(): bool
    {
        return ! isset($this->publicKeyPath) && ! isset($this->privateKeyPath);
    }

    public function isIncomplete(): bool
    {
        return ! isset($this->publicKeyPath) || ! isset($this->privateKeyPath);
    }
}
