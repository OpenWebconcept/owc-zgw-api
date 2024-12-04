<?php

declare(strict_types=1);

namespace OWC\ZGW\Http\WordPress;

use WP_Error;
use OWC\ZGW\Http\RequestError;

class WordPressRequestError extends RequestError
{
    public static function fromWpError(WP_Error $error): self
    {
        return new self($error->get_error_message());
    }
}
