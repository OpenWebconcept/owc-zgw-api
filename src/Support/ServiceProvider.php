<?php

declare(strict_types=1);

namespace OWC\ZGW\Support;

use DI\Container;

abstract class ServiceProvider
{
    protected Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->boot();
    }

    public function boot(): void
    {
        // override
    }

    abstract public function register(): void;
}
