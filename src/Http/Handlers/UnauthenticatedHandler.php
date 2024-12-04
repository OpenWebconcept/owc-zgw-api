<?php

declare(strict_types=1);

namespace OWC\ZGW\Http\Handlers;

use OWC\ZGW\Http\Response;
use OWC\ZGW\Http\Errors\UnauthenticatedError;

class UnauthenticatedHandler implements HandlerInterface
{
    public function handle(Response $response): Response
    {
        if (! in_array($response->getResponseCode(), [401, 403])) {
            return $response;
        }

        throw UnauthenticatedError::fromResponse($response);
    }
}
