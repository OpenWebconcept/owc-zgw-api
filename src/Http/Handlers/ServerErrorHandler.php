<?php

declare(strict_types=1);

namespace OWC\ZGW\Http\Handlers;

use OWC\ZGW\Http\Response;
use OWC\ZGW\Http\Errors\ServerError;

class ServerErrorHandler implements HandlerInterface
{
    public function handle(Response $response): Response
    {
        if ($response->getResponseCode() !== 500) {
            return $response;
        }

        throw ServerError::fromResponse($response);
    }
}
