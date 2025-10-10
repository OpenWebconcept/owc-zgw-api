<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities;

use OWC\ZGW\Entities\Attributes\Confidentiality;

class Enkelvoudiginformatieobject extends Entity
{
    protected array $casts = [
        // url
        // identificatie
        // bronorganisatie
        'creatiedatum' => Casts\NullableDate::class,
        // titel
        // vertrouwelijkheidaanduiding
        'vertrouwelijkheidaanduiding' => Casts\Confidentiality::class,
        // auteur
        'status' => Casts\Status::class,
        // formaat
        // taal
        // versie
        'beginRegistratie' => Casts\NullableDateTime::class,
        // bestandsnaam
        // inhoud
        // bestandsomvang
        // link
        // beschrijving
        'ontvangstdatum' => Casts\NullableDate::class,
        'verzenddatum' => Casts\NullableDate::class,
        // indicatieGebruiksrecht
        // ondertekening
        // integriteit
        // informatieobjecttype
        // locked
        // bestandsdelen
    ];

    public function hasFinalStatus(): bool
    {
        if ($this->hasReceiptDate()) {
            return true;
        }

        return (bool) $this->status?->hasFinalStatus();
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

    public function displayAllowedByConfidentialityDesignation(): bool
    {
        return $this->vertrouwelijkheidaanduiding->isDisplayAllowed();
    }

    public function isClassified(): bool
    {
        return $this->vertrouwelijkheidaanduiding->isClassified();
    }
}
