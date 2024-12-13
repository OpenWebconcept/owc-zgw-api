<?php

namespace OWC\ZGW\Entities\Traits;

use OWC\ZGW\Entities\Casts\CastsAttributes;

trait HasCastableAttributes
{
    /**
     * @param mixed $default
     *
     * @return mixed
     */
    public function getAttributeValue(string $name, $default = null)
    {
        return $this->data[$name] ?? $default;
    }

    /** @param mixed $value */
    public function setAttributeValue(string $name, $value): void
    {
        $this->data[$name] = $value;
    }

    public function hasAttributeValue(string $name): bool
    {
        return isset($this->data[$name]);
    }

    /** @return string[] */
    public function getAttributeNames(): array
    {
        return array_keys($this->data);
    }

    protected function hasCast(string $name): bool
    {
        return isset($this->casts[$name]);
    }

    protected function getCaster(string $name): CastsAttributes
    {
        $caster = $this->casts[$name];

        if (strpos($caster, ':') === false) {
            return new $caster();
        }

        [$class, $constructorArg] = explode(':', $caster);

        return new $class($constructorArg);
    }
}
