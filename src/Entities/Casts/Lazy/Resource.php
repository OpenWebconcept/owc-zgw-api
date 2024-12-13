<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts\Lazy;

use OWC\ZGW\Entities\Entity;
use OWC\ZGW\Contracts\Client;
use OWC\ZGW\Entities\Casts\AbstractCast;

use function OWC\ZGW\apiClientManager;

abstract class Resource extends AbstractCast
{
    protected string $registryType = 'registry';
    protected string $resourceType = Entity::class;

    public function set(Entity $model, string $key, $value)
    {
        if (is_null($value) || is_string($value) || (is_object($value) && $value instanceof Entity)) {
            return $value;
        }

        // Build an entity from the given data. This usually happens when a
        // resource has beeen included through the expand functionality.
        if (is_array($value)) {
            return $this->buildResource($value, $model);
        }

        throw new InvalidResourceValue(sprintf(
            'Invalid "%s" given, expected <string> or <%s>, got <%s>',
            $key,
            Entity::class,
            is_object($value) ? get_class($value) : gettype($value)
        ));
    }

    public function get(Entity $model, string $key, $value): ?Entity
    {
        if (! is_string($value)) {
            return $value; // Nullable or Entity
        }

        $client = $model->client();

        if ($this->isUrl($value)) {
            $client = apiClientManager()->clientFromUrl($value, $this->registryType);

            $value = $this->getUuidFromUrl($value);
        }

        if (! $client) {
            throw new ZgwClientNotFound(
                "Unable to resolve '{$key}' entity: the required ZGW client is not (properly) configured."
            );
        }

        $entity = $this->resolveResource($client, $value);

        // Assign the resolved zaaktype back to the model, so we won't
        // load it again whenever this attribute is accessed again.
        $model->setAttributeValue($key, $entity);

        return $entity;
    }

    public function serialize(string $name, $value)
    {
        if (is_object($value) && $value instanceof Entity) {
            return $value->url;
        }

        return $value;
    }

    abstract protected function resolveResource(Client $client, string $uuid): ?Entity;

    /** @param array<mixed> $itemData */
    protected function buildResource(array $itemData, Entity $model): Entity
    {
        return new $this->resourceType($itemData, $model->client());
    }

    protected function isUrl(string $value): bool
    {
        return (bool) filter_var($value, FILTER_VALIDATE_URL);
    }

    protected function getUuidFromUrl(string $url): string
    {
        return substr($url, strrpos($url, '/') + 1);
    }
}
