<?php

declare(strict_types=1);

namespace OWC\ZGW\Http\Errors;

use OWC\ZGW\Http\RequestError;
use OWC\ZGW\Http\Response;

class BadRequestError extends RequestError
{
    protected array $invalidParameters = [];

    public static function fromResponse(Response $response)
    {
        $error = parent::fromResponse($response);

        if ($error->code === 0) {
            // Unhandable error.
            return $error;
        }

        $json = $response->getParsedJson();

        $error->setInvalidParameters($json['invalidParams'] ?? []);

        return $error;
    }

    public function setInvalidParameters(array $parameters)
    {
        $this->invalidParameters = $parameters;

        return $this;
    }

    public function getInvalidParameters(): array
    {
        return $this->invalidParameters;
    }

    public function hasInvalidParameters(): bool
    {
        return ! empty($this->invalidParameters);
    }
}
