<?php

declare(strict_types=1);

namespace OWC\ZGW\Http\Handlers;

use OWC\ZGW\Http\Response;
use OWC\ZGW\Http\Errors\BadRequestError;

class BadRequestHandler implements HandlerInterface
{
    public function handle(Response $response): Response
    {
        if ($response->getResponseCode() !== 400) {
            return $response;
        }

        throw BadRequestError::fromResponse($response);
    }
}
