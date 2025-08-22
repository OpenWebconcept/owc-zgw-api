<?php

declare(strict_types=1);

namespace OWC\ZGW\Http;

class Response
{
    public function __construct(
        protected array $headers,
        protected array $response,
        protected string $body,
        protected array $cookies = [],
        protected array $json = []
    ) {
        $this->json = $this->parseAsJson($this->body);
    }

    /** @return array<mixed> */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getContentType(): string
    {
        return $this->headers['content-type'] ?? '';
    }

    /** @return array<mixed> */
    public function getResponse(): array
    {
        return $this->response;
    }

    public function getResponseCode(): ?int
    {
        return $this->response['code'] ?? null;
    }

    public function getResponseMessage(): ?string
    {
        return $this->response['message'] ?? null;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    /** @param array<mixed> $json */
    public function modify(array $json): self
    {
        $this->json = $json;

        return $this;
    }

    /** @return array<mixed> */
    public function getParsedJson(): array
    {
        return $this->json;
    }

    /** @return array<mixed> */
    protected function parseAsJson(string $body): array
    {
        $decoded = json_decode($body, true, 512);

        if (! is_array($decoded)) {
            return [];
        }

        return $decoded;
    }

    /** @return array<mixed> */
    public function getCookies(): array
    {
        return $this->cookies;
    }
}
