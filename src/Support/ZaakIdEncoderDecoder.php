<?php

declare(strict_types=1);

namespace OWC\ZGW\Traits;

class ZaakIdEncoderDecoder
{
    /**
     * Converts slashes ("/") in a 'zaak' identification to double dashes ("--").
     * This ensures compatibility with the routing system, which does not support slashes in identifiers.
     */
    public static function encode(string $identification): string
    {
        return str_replace('/', '--', $identification);
    }

    /**
     * Restores the original 'zaak' identification by replacing double dashes ("--") with slashes ("/").
     * This is used to decode the 'zaak' identification back to its original form.
     */
    public static function decode(string $identification): string
    {
        return str_replace('--', '/', $identification);
    }
}
