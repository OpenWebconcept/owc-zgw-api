<?php

declare(strict_types=1);

namespace OWC\ZGW\Http;

class RequestOptions
{
    /** @var array<mixed> */
    protected array $options = [
        'headers' => [],
        'body' => [],
        'cookies' => [],
    ];

    /** @param array<mixed>|null $options */
    public function __construct(?array $options = [])
    {
        $this->options = $options;
    }

    public function set(string $name, mixed $value): self
    {
        $this->options[$name] = $value;

        return $this;
    }

    public function get(string $name, mixed $default = null): mixed
    {
        return $this->options[$name] ?? $default;
    }

    public function has(string $name): bool
    {
        return isset($this->options[$name]);
    }

    public function addHeader(string $name, mixed $value): self
    {
        $this->options['headers'][$name] = $value;

        return $this;
    }

    public function getHeader(string $name, mixed $default = null): mixed
    {
        return $this->options['headers'][$name] ?? $default;
    }

    public function getHeaders(): array
    {
        return $this->options['headers'];
    }

    public function addCookie(string $name, mixed $value): self
    {
        $this->options['cookies'][$name] = $value;

        return $this;
    }

    public function getCookie(string $name, mixed $default = null): mixed
    {
        return $this->options['cookies'][$name] ?? $default;
    }

    /**
     * @return array<mixed>
     */
    public function getCookies(): array
    {
        return $this->options['headers'];
    }

    public function merge(RequestOptions $options): self
    {
        $this->options = array_merge_recursive($this->options, $options->toArray());

        return $this;
    }

    public function clone(): self
    {
        return clone $this;
    }

    /**
     * @return array<mixed>
     */
    public function toArray(): array
    {
        return $this->options;
    }
}
