<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities;

class Informatieobjecttype extends Entity
{
    protected array $casts = [
        // url
        'catalogus' => Casts\Lazy\Catalogus::class,
        // omschrijving
        'vertrouwelijkheidaanduiding' => Casts\Confidentiality::class,
        'beginGeldigheid' => Casts\NullableDate::class,
        'eindeGeldigheid' => Casts\NullableDate::class,
        // concept - boolean
    ];
}
