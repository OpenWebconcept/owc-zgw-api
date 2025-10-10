<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Attributes;

enum Confidentiality: string
{
    case OPENBAAR = 'openbaar';
    case BEPERKT_OPENBAAR = 'beperkt_openbaar';
    case INTERN = 'intern';
    case ZAAKVERTROUWELIJK = 'zaakvertrouwelijk';
    case VERTROUWELIJK = 'vertrouwelijk';
    case CONFIDENTIEEL = 'confidentieel';
    case GEHEIM = 'geheim';
    case ZEER_GEHEIM = 'zeer_geheim';
    
    public function is(Confidentiality $level): bool
    {
        return $this === $level;
    }

    public function isnt(Confidentiality $level): bool
    {
        return $this !== $level;
    }

    public function isCaseConfidential(): bool
    {
        return $this === self::ZAAKVERTROUWELIJK;
    }

    public function isConfidential(): bool
    {
        return $this === self::VERTROUWELIJK;
    }

    public function isClassified(): bool
    {
        return match ($this) {
            self::INTERN,
            self::CONFIDENTIEEL,
            self::GEHEIM,
            self::ZEER_GEHEIM => true,
            default => false,
        };
    }

    public function isDisplayAllowed(): bool
    {
        return match ($this) {
            self::OPENBAAR,
            self::BEPERKT_OPENBAAR,
            self::INTERN,
            self::ZAAKVERTROUWELIJK => true,
            default => false,
        };
    }

}
