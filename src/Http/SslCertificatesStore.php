<?php

declare(strict_types=1);

namespace OWC\ZGW\Http;

class SslCertificatesStore
{
    public function __construct(
        protected string $publicKeyPath,
        protected string $privateKeyPath
    ) {
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
