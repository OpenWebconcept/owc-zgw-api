<?php

declare(strict_types=1);

namespace OWC\ZGW\Http\Handlers;

use OWC\ZGW\Http\Response;
use OWC\ZGW\Http\Errors\ResourceNotFoundError;

class ResourceNotFoundHandler implements HandlerInterface
{
    public function handle(Response $response): Response
    {
        if ($response->getResponseCode() !== 404) {
            return $response;
        }

        throw ResourceNotFoundError::fromResponse($response);
    }
}
