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

    /**
     * @param mixed $value
     */
    public function set(string $name, $value): self
    {
        $this->options[$name] = $value;

        return $this;
    }

    /**
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(string $name, $default = null)
    {
        return $this->options[$name] ?? $default;
    }

    public function has(string $name): bool
    {
        return isset($this->options[$name]);
    }

    /**
     * @param mixed $value
     */
    public function addHeader(string $name, $value): self
    {
        $this->options['headers'][$name] = $value;

        return $this;
    }

    /**
     * @param mixed $default
     *
     * @return mixed
     */
    public function getHeader(string $name, $default = null)
    {
        return $this->options['headers'][$name] ?? $default;
    }

    /**
     * @return array<mixed>
     */
    public function getHeaders(): array
    {
        return $this->options['headers'];
    }

    /**
     * @param mixed $value
     */
    public function addCookie(string $name, $value): self
    {
        $this->options['cookies'][$name] = $value;

        return $this;
    }

    /**
     * @param mixed $default
     *
     * @return mixed
     */
    public function getCookie(string $name, $default = null)
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
