<?php

declare(strict_types=1);

namespace OWC\ZGW\Http\Curl;

use OWC\ZGW\Http\Response;

class CurlClientResponse extends Response
{
    /**
     * @param resource $handle
     */
    public static function fromResponse(string $response, $handle): self
    {
        return new self(
            [], // No headers
            [
                'code' => curl_getinfo($handle, CURLINFO_HTTP_CODE),
                'message' => '',
            ],
            $response,
            [], // No cookies
        );
    }
}
