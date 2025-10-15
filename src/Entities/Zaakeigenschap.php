<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities;

use OWC\ZGW\Entities\Attributes\Confidentiality;

class Zaakeigenschap extends Entity
{
    protected array $casts = [
        // 'url' => "http://example.com",
        // 'uuid' => "095be615-a8ad-4c33-8e9c-c7612fbf6c9f",
        'vertrouwelijkheidaanduiding' => Casts\Confidentiality::class,
        'status' => Casts\Status::class,
        'zaak' => Casts\Lazy\Zaak::class,
        // 'eigenschap' => "http://example.com",
        // 'naam' => "string",
        // 'waarde' => "string",
    ];

    public function hasFinalStatus(): bool
    {
        if ($this->hasReceiptDate()) {
            return true;
        }

        $finalStatusses = ['definitief', 'gearchiveerd'];

        return in_array((string) $this->status, $finalStatusses);
    }

    public function hasReceiptDate(): bool
    {
        return (bool) $this->ontvangstdatum;
    }

    public function isCaseConfidential(): bool
    {
        return $this->vertrouwelijkheidaanduiding->is(Confidentiality::ZAAKVERTROUWELIJK);
    }

    public function isConfidential(): bool
    {
        return $this->vertrouwelijkheidaanduiding->is(Confidentiality::VERTROUWELIJK);
    }

    public function isDisplayAllowed(): bool
    {
        return $this->vertrouwelijkheidaanduiding->isDisplayAllowed();
    }

    public function isClassified(): bool
    {
        return $this->vertrouwelijkheidaanduiding->isClassified();
    }
}
