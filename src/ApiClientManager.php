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

        foreach ($endpoints as $clientName => $urlCollection) {
            $endpoint = $urlCollection->get($registry);

            if (strpos($url, $endpoint) === 0) {
                return $this->getClient($clientName);
            }
        }

        return null;
    }

    public function addClient(
        string $name,
        string $client,
        ApiCredentials $credentials,
        ApiUrlCollection $endpoints
    ) {
        if (! class_exists($client)) {
            throw new \InvalidArgumentException("Unknown Client implementation");
        }

        $this->container->set($name.'client', $client);
        $this->container->set($name.'endpoints', $endpoints);
        $this->container->set($name.'credentials', $credentials);

        $this->container->get('api.endpoints')->set($name, $endpoints);
    }

    public function buildClient(string $name)
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
