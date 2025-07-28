<?php

declare(strict_types=1);

namespace OWC\ZGW\Http;

interface RequestClientInterface
{
    public function __construct(?RequestOptions $options = null);
    public function setRequestOptions(RequestOptions $options): self;
    public function getRequestOptions(): RequestOptions;
    public function addSslCertificates(SslCertificatesStore $store): self;
    public function get(string $url, ?RequestOptions $options = null): Response;
    public function post(string $url, mixed $body, ?RequestOptions $options = null): Response;
    public function delete(string $url, ?RequestOptions $options = null): Response;
}
