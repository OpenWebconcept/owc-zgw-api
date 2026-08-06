<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Casts\Lazy;

use OWC\ZGW\Contracts\Client;

trait ResolvesClientFromUrl
{
    protected string $registryType = 'registry';

    protected function isUrl(string $value): bool
    {
        return (bool) filter_var($value, FILTER_VALIDATE_URL);
    }

    /**
     * A model's own client already targets the correct register, so it
     * must be preferred over a global URL-based lookup, which cannot
     * disambiguate between multiple registered clients of the same
     * supplier (e.g. two registers sharing the same host).
     */
    protected function belongsToClient(string $url, ?Client $client): bool
    {
        if (! $client) {
            return false;
        }

        $endpoint = $client->getEndpointUrlByType($this->registryType);

        return ! empty($endpoint) && strpos($url, $endpoint) === 0;
    }

    protected function getUuidFromUrl(string $url): string
    {
        return substr($url, strrpos($url, '/') + 1);
    }
}
