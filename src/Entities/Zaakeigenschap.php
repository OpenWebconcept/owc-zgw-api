<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities;

class Zaakeigenschap extends Entity
{
    protected array $casts = [
        // 'url' => "http://example.com",
        // 'uuid' => "095be615-a8ad-4c33-8e9c-c7612fbf6c9f",
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
        $designation = (string) $this->vertrouwelijkheidaanduiding;

        return 'zaakvertrouwelijk' === $designation;
    }

    public function isConfidential(): bool
    {
        $designation = (string) $this->vertrouwelijkheidaanduiding;

        return 'vertrouwelijk' === $designation;
    }

    public function displayAllowedByConfidentialityDesignation(): bool
    {
        $designation = (string) $this->vertrouwelijkheidaanduiding;

        $allowedDesignations = [
            'openbaar',
            'beperkt_openbaar',
            'intern',
            'zaakvertrouwelijk',
        ];

        return in_array(strtolower($designation), $allowedDesignations, true);
    }

    public function isClassified(): bool
    {
        $designation = (string) $this->vertrouwelijkheidaanduiding;

        $classifiedDesignations = [
            'intern',
            'confidentieel',
            'geheim',
            'zeer_geheim',
        ];

        return in_array($designation, $classifiedDesignations);
    }
}
