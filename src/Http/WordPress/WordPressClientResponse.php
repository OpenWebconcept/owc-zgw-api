<?php

declare(strict_types=1);

namespace OWC\ZGW\Http\WordPress;

use OWC\ZGW\Http\Response;

class WordPressClientResponse extends Response
{
    public static function fromResponse(array $response): self
    {
        return new self(
            isset($response['headers']) ? $response['headers']->getAll() : [],
            $response['response'] ?? [],
            $response['body'] ?? '',
            $response['cookies'] ?? [],
        );
    }
}
