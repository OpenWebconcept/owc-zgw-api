<?php

namespace OWC\ZGW;

use DI\Container;
use DI\ContainerBuilder;
use OWC\ZGW\Contracts\Client;

class ApiClientManager
{
    protected Container $container;

    public function __construct()
    {
        $this->setupContainer();
    }

    public function container(): Container
    {
        return $this->container;
    }

    public function getClient(string $name): Client
    {
        return $this->buildClient($name);
    }

    public function clientFromUrl(string $url, string $registry): ?Client
    {
        $endpoints = $this->container->get('api.endpoints');

        $matchedClientName = null;
        $matchedEndpointLength = -1;

        foreach ($endpoints as $clientName => $urlCollection) {
            $endpoint = $urlCollection->get($registry);

            if (empty($endpoint) || strpos($url, $endpoint) !== 0) {
                continue;
            }

            // Multiple registered clients can share the same host/prefix
            // (e.g. two registers with the same supplier). Prefer the
            // longest matching endpoint, since it is the most specific one.
            if (strlen($endpoint) > $matchedEndpointLength) {
                $matchedClientName = $clientName;
                $matchedEndpointLength = strlen($endpoint);
            }
        }

        return $matchedClientName ? $this->getClient($matchedClientName) : null;
    }

    public function addClient(
        string $name,
        string $client,
        ApiCredentials $credentials,
        ApiUrlCollection $endpoints
    ): void {
        if (! class_exists($client)) {
            throw new \InvalidArgumentException("Unknown Client implementation");
        }

        $this->container->set($name.'client', $client);
        $this->container->set($name.'endpoints', $endpoints);
        $this->container->set($name.'credentials', $credentials);

        $this->container->get('api.endpoints')->set($name, $endpoints);
    }

    public function buildClient(string $name): Client
    {
        $client = $this->container->get($name.'client');
        $endpoints = $this->container->get($name.'endpoints');
        $credentials = $this->container->get($name.'credentials');

        return $this->container->make($client, compact('credentials', 'endpoints'));
    }

    protected function setupContainer(): void
    {
        if (! empty($GLOBALS['zgwApiClientManager'])) {
            $this->container = $GLOBALS['zgwApiClientManager']->container();

            return;
        }

        $builder = new ContainerBuilder();
        $builder->addDefinitions(dirname(__DIR__) . '/config/container.php');
        $this->container = $builder->build();
        $GLOBALS['zgwApiClientManager'] = $this;
    }
}
