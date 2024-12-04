<?php

declare(strict_types=1);

namespace OWC\ZGW\Http\Handlers;

use OWC\ZGW\Http\Response;

interface HandlerInterface
{
    public function handle(Response $response): Response;
}
