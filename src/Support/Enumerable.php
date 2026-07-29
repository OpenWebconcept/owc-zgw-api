<?php

declare(strict_types=1);

namespace OWC\ZGW\Support;

use Iterator;
use ArrayAccess;

/**
 * @implements ArrayAccess<int|string, mixed>
 * @implements Iterator<int|string, mixed>
 */
abstract class Enumerable implements ArrayAccess, Iterator
{
    /** @var array<int|string, mixed> */
    protected iterable $data;

    /** @param array<int|string, mixed>|iterable<int|string, mixed> $data */
    public function __construct(iterable $data)
    {
        $this->hydrate($data);
    }

    public function __get(string $key): mixed
    {
        return $this->data[$key];
    }

    public function __set(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }

    public function __isset(string $key)
    {
        return isset($this->data[$key]);
    }

    public function __unset(string $key)
    {
        unset($this->data[$key]);
    }

    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset): void
    {
        if ($this->offsetExists($offset)) {
            unset($this->data[$offset]);
        }
    }

    public function offsetGet($offset): mixed
    {
        return $this->offsetExists($offset) ? $this->data[$offset] : null;
    }

    public function rewind(): void
    {
        reset($this->data);
    }

    public function current(): mixed
    {
        return current($this->data);
    }

    public function key(): mixed
    {
        return key($this->data);
    }

    public function next(): void
    {
        next($this->data);
    }

    public function valid(): bool
    {
        return key($this->data) !== null;
    }

    /** @return array<int|string, mixed> */
    public function toArray(): iterable
    {
        return $this->data;
    }

    /** @param iterable<int|string, mixed> $data */
    protected function hydrate(iterable $data): void
    {
        $this->data = is_array($data) ? $data : iterator_to_array($data);
    }
}
