<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts\Lazy;

use Exception;
use OWC\ZGW\Entities\Entity;
use OWC\ZGW\Contracts\Client;
use OWC\ZGW\Support\Collection;
use OWC\ZGW\Entities\Casts\AbstractCast;

use function OWC\ZGW\apiClientManager;

abstract class ResourceCollection extends AbstractCast
{
    protected string $registryType = 'registry';

    public function set(Entity $model, string $key, $value)
    {
        if (! is_iterable($value)) {
            throw new InvalidResourceValue(sprintf(
                'Invalid "%s" given, expected Iterable value',
                $key
            ));
        }

        // This check will be redundant from PHP 8.2 onward as array_filter
        // will then accept both arrays and Iterable objects.
        if (! is_array($value)) {
            $value = iterator_to_array($value);
        }

        return array_filter($value, function ($item) {
            return is_null($item)
                || is_string($item)
                || (is_object($item) && $item instanceof Entity);
        });
    }

    public function get(Entity $model, string $key, $value): ?Collection
    {
        if (! is_iterable($value)) {
            throw new InvalidResourceValue(sprintf(
                'Unable to cast "%s" to %s; value is not iterable',
                $key,
                static::class
            ));
        }

        $collection = Collection::collect($value)->map(function ($item) use ($model, $key) {
            if (is_object($item) && $item instanceof Entity) {
                return $item;
            }

            $client = $model->client();

            if ($this->isUrl($item)) {
                $client = apiClientManager()->clientFromUrl($item, $this->registryType);
                $item = $this->getUuidFromUrl($item);
            }

            if (! $client) {
                throw new ZgwClientNotFound(
                    "Unable to resolve '{$key}' entity: the required ZGW client is not (properly) configured for '{$item}'."
                );
            }

            try {
                return $this->resolveResource($client, $item);
            } catch (Exception $e) {
                return null;
            }
        })->filter(function ($item) {
            return $item instanceof Entity;
        });

        // Assign the resolved zaaktype back to the model, so we won't
        // load it again whenever this attribute is accessed again.
        $model->setAttributeValue($key, $collection);

        return $collection;
    }

    public function serialize(string $name, $value)
    {
        return array_map(function ($item) {
            if (is_object($item) && $item instanceof Entity) {
                return $item->url;
            }

            return $item;
        }, $value);
    }

    abstract protected function resolveResource(Client $client, string $uuid): ?Entity;

    protected function isUrl(string $value): bool
    {
        return (bool) filter_var($value, FILTER_VALIDATE_URL);
    }

    protected function getUuidFromUrl(string $url): string
    {
        return substr($url, strrpos($url, '/') + 1);
    }
}
