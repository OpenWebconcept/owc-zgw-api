<?php

declare(strict_types=1);

namespace OWC\ZGW\Support;

use Closure;

class Collection extends Enumerable implements CollectionInterface
{
    use Sortable;

    public const SORT_REVERSE = true;

    public static function collect(iterable $data): self
    {
        return new self($data);
    }

    public function get(int|string $key, mixed $default = null): mixed
    {
        return $this->has($key) ? $this->data[$key] : $default;
    }

    public function set(int|string $key, mixed $value): static
    {
        $this->data[$key] = $value;

        return $this;
    }

    public function has(int|string $key): bool
    {
        return isset($this->data[$key]);
    }

    /** @return array<int|string, mixed> */
    public function all(): iterable
    {
        return $this->data;
    }

    /** @return array<int|string, mixed> */
    public function take(int $limit): iterable
    {
        return array_slice($this->data, 0, $limit);
    }

    public function push(mixed $item): static
    {
        $this->data[] = $item;

        return $this;
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function isEmpty(): bool
    {
        return empty($this->data);
    }

    public function isNotEmpty(): bool
    {
        return $this->isEmpty() === false;
    }

    public function nth(int $index, mixed $default): mixed
    {
        return $this->get($index, $default);
    }

    public function first(?Closure $callback = null): mixed
    {
        if (! $callback) {
            return reset($this->data);
        }

        foreach ($this->data as $key => $value) {
            $result = $callback($key, $value);

            if ($result) {
                return $result;
            }
        }

        return null;
    }

    public function last(): mixed
    {
        return end($this->data);
    }

    public function keys(): self
    {
        return static::collect(array_keys($this->data));
    }

    public function filter(Closure $predicate): self
    {
        return static::collect(array_filter($this->data, $predicate));
    }

    public function map(Closure $callback): self
    {
        return static::collect(array_map($callback, $this->data));
    }

    public function mapWithKeys(Closure $callback): self
    {
        return static::collect(array_map($callback, array_keys($this->data), $this->data));
    }

    public function flatten(Closure $callback, mixed $initial = null): mixed
    {
        return array_reduce($this->data, $callback, $initial);
    }

    public function flattenAndAssign(Closure $callback, mixed $initial = null): static
    {
        $this->data = array_reduce($this->data, $callback, $initial);

        return $this;
    }

    public function groupBy(Closure $callback): self
    {
        $results = [];

        foreach ($this->data as $key => $value) {
            $groupKeys = $callback($value, $key);

            if (! is_array($groupKeys)) {
                $groupKeys = [$groupKeys];
            }

            foreach ($groupKeys as $groupKey) {
                $groupKey = is_bool($groupKey) ? (int) $groupKey : $groupKey;

                if (! array_key_exists($groupKey, $results)) {
                    $results[$groupKey] = new static([]);
                }

                $results[$groupKey]->offsetSet($key, $value);
            }
        }

        return static::collect($results);
    }
}
