<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities;

use Exception;
use ArrayAccess;
use JsonSerializable;
use OWC\ZGW\Contracts\Client;
use OWC\ZGW\Traits\ZaakIdentification;

abstract class Entity implements
    ArrayAccess,
    JsonSerializable,
    Contracts\Jsonable,
    Contracts\Arrayable
{
    use Traits\Arrayable;
    use Traits\Macroable;
    use Traits\HasCastableAttributes;
    use ZaakIdentification;

    protected Client $client;

    /** @var array<mixed> */
    protected array $data = [];

    /** @var array<string, class-string> */
    protected array $casts = [];

    /** @param array<mixed> $itemData */
    public function __construct(array $itemData, Client $client)
    {
        $this->client = $client;
        $this->hydrate($itemData);
    }

    public function __get(string $name): mixed
    {
        try {
            return $this->getValue($name);
        } catch (Exception $e) {
            return null; // Returning null is in line with the return types of the methods inside the cast classes.
        }
    }

    public function __set(string $name, mixed $value): void
    {
        $this->setValue($name, $value);
    }

    public function __debugInfo(): array
    {
        return ['data' => $this->data];
    }

    public function getValue(string $name, mixed $default = null): mixed
    {
        $value = $this->getAttributeValue($name, $default);

        if ($this->hasCast($name)) {
            $caster = $this->getCaster($name);

            return $caster->get($this, $name, $value);
        }

        return $value;
    }

    public function setValue(string $name, mixed $value): void
    {
        if ($this->hasCast($name)) {
            $caster = $this->getCaster($name);

            try {
                $value = $caster->set($this, $name, $value);
            } catch (Exception $e) {
                return;
            }
        }

        $this->setAttributeValue($name, $value);
    }

    public function toArray(): array
    {
        $data = [];
        foreach ($this->getAttributeNames() as $name) {
            $data[$name] = $this->serializeAttribute($name);
        }

        return $data;
    }

    public function attributesToArray(): array
    {
        return $this->data;
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function toJson(int $flags = 0, int $depth = 512): string
    {
        return json_encode($this, $flags, $depth);
    }

    public function client(): Client
    {
        return $this->client;
    }

    protected function serializeAttribute(string $name): mixed
    {
        if (! $this->hasCast($name)) {
            return $this->getValue($name);
        }

        return $this->getCaster($name)->serialize($name, $this->getAttributeValue($name));
    }

    protected function hydrate(array $data): void
    {
        foreach ($data as $name => $value) {
            $this->setValue($name, $value);
        }
    }
}
