<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities;

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
        // beginRegistratie
        // bestandsnaam
        // inhoud
        // bestandsomvang
        // link
        // beschrijving
        // ontvangstdatum
        // verzenddatum
        // indicatieGebruiksrecht
        // ondertekening
        // integriteit
        // informatieobjecttype
        // locked
        // bestandsdelen
    ];
}
