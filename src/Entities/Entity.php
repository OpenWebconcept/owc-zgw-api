<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities;

use Exception;
use ArrayAccess;
use JsonSerializable;
use OWC\ZGW\Contracts\Client;

abstract class Entity implements
    ArrayAccess,
    JsonSerializable,
    Contracts\Jsonable,
    Contracts\Arrayable
{
    use Traits\Arrayable;
    use Traits\Macroable;
    use Traits\HasCastableAttributes;

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

    /** @return mixed */
    public function __get(string $name)
    {
        try {
            return $this->getValue($name);
        } catch (Exception $e) {
            return null; // Returning null is in line with the return types of the methods inside the cast classes.
        }
    }

    /**
     * @param mixed $value
     *
     * @return mixed
     */
    public function __set(string $name, $value)
    {
        return $this->setValue($name, $value);
    }

    /**
     * @param mixed $default
     *
     * @return mixed
     */
    public function getValue(string $name, $default = null)
    {
        $value = $this->getAttributeValue($name, $default);

        if ($this->hasCast($name)) {
            $caster = $this->getCaster($name);

            return $caster->get($this, $name, $value);
        }

        return $value;
    }

    /**
     * @param mixed $value
     */
    public function setValue(string $name, $value): void
    {
        if ($this->hasCast($name)) {
            $caster = $this->getCaster($name);

            try {
                $value = $caster->set($this, $name, $value);
            } catch (Exception $e) {
                return;
            }
        }

        return $this->setAttributeValue($name, $value);
    }

    /** @return mixed */
    public function toArray(): array
    {
        $data = [];
        foreach ($this->getAttributeNames() as $name) {
            $data[$name] = $this->serializeAttribute($name);
        }

        return $data;
    }

    /** @return mixed */
    public function attributesToArray(): array
    {
        return $this->data;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
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

    /** @return mixed */
    protected function serializeAttribute(string $name)
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

    public function __debugInfo(): array
    {
        return ['data' => $this->data];
    }
}
