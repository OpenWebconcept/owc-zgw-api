<?php

declare(strict_types=1);

namespace OWC\ZGW\Http\Errors;

use OWC\ZGW\Http\Response;
use OWC\ZGW\Http\RequestError;

class BadRequestError extends RequestError
{
    /** @var array<mixed> */
    protected array $invalidParameters = [];

    public static function fromResponse(Response $response): parent
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

    /** @param array<mixed> $parameters */
    public function setInvalidParameters(array $parameters): self
    {
        $this->invalidParameters = $parameters;

        return $this;
    }

    /** @return array<mixed> */
    public function getInvalidParameters(): array
    {
        return $this->invalidParameters;
    }

    public function hasInvalidParameters(): bool
    {
        return ! empty($this->invalidParameters);
    }
}
