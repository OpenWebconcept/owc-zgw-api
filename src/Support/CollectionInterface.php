<?php

declare(strict_types=1);

namespace OWC\ZGW\Support;

use Closure;

interface CollectionInterface
{
    /** @param iterable<int|string, mixed> $data */
    public static function collect(iterable $data): CollectionInterface;

    public function get(int|string $key, mixed $default = null): mixed;
    public function has(int|string $key): bool;

    /** @return array<int|string, mixed> */
    public function all(): iterable;

    public function count(): int;
    public function isEmpty(): bool;
    public function isNotEmpty(): bool;
    public function nth(int $index, mixed $default): mixed;
    public function first(): mixed;
    public function last(): mixed;
    public function filter(Closure $predicate): CollectionInterface;
    public function map(Closure $callback): CollectionInterface;

    /** @return array<int|string, mixed> */
    public function toArray(): iterable;
}
