<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities;

use DateTimeImmutable;
use OWC\ZGW\Support\Collection;
use OWC\ZGW\Support\PagedCollection;
use OWC\ZGW\Traits\ZaakIdentification;

/**
 * @property ?string $url
 * @property ?string $uuid
 * @property ?string $identificatie
 * @property ?string $bronorganisatie
 * @property ?string $omschrijving
 * @property ?string $toelichting
 * @property ?\OWC\ZGW\Entities\Zaaktype $zaaktype
 * @property ?\DateTimeImmutable $registratiedatum
 * @property ?string $verantwoordelijkeOrganisatie
 * @property ?\DateTimeImmutable $startdatum
 * @property ?\DateTimeImmutable $einddatum
 * @property ?\DateTimeImmutable $einddatumGepland
 * @property ?\DateTimeImmutable $uiterlijkeEinddatumAfdoening
 * @property ?\DateTimeImmutable $publicatiedatum
 * @property ?string $communicatiekanaal
 * @property ?string $productenOfDiensten
 * @property \OWC\ZGW\Entities\Attributes\Confidentiality $vertrouwelijkheidaanduiding
 * @property ?string $betalingsindicatie
 * @property ?string $betalingsindicatieWeergave
 * @property ?\DateTimeImmutable $laatsteBetaaldatum
 * @property ?string $zaakgeometrie
 * @property ?string $verlenging
 * @property ?string $opschorting
 * @property ?string $selectielijstklasse
 * @property ?\OWC\ZGW\Entities\Zaak $hoofdzaak
 * @property ?\OWC\ZGW\Support\Collection $deelzaken
 * @property ?string $relevanteAndereZaken
 * @property ?string $eigenschappen
 * @property ?\OWC\ZGW\Entities\Status $status
 * @property ?string $kenmerken
 * @property ?string $archiefnominatie
 * @property ?string $archiefstatus
 * @property ?\DateTimeImmutable $archiefactiedatum
 * @property ?\OWC\ZGW\Entities\Resultaat $resultaat
 * @property ?string $opdrachtgevendeOrganisatie
 * @property ?\OWC\ZGW\Support\Collection $statussen
 * @property ?\OWC\ZGW\Support\Collection $zaakinformatieobjecten
 * @property ?\OWC\ZGW\Support\Collection $rollen
 */
class Zaak extends Entity
{
    use ZaakIdentification;

    protected array $casts = [
        'url' => Casts\Url::class,
        // 'uuid'  => SomeClass::class,
        // 'identificatie' => SomeClass::class,
        // 'bronorganisatie'   => SomeClass::class,
        // 'omschrijving'  => SomeClass::class,
        // 'toelichting'   => SomeClass::class,
        'zaaktype' => Casts\Lazy\Zaaktype::class,
        'registratiedatum' => Casts\NullableDate::class,
        // 'verantwoordelijkeOrganisatie'  => SomeClass::class,
        'startdatum' => Casts\NullableDate::class,
        'einddatum' => Casts\NullableDate::class,
        'einddatumGepland' => Casts\NullableDate::class,
        'uiterlijkeEinddatumAfdoening' => Casts\NullableDate::class,
        'publicatiedatum' => Casts\NullableDate::class,
        // 'communicatiekanaal'    => SomeClass::class,
        // 'productenOfDiensten'   => SomeClass::class,
        'vertrouwelijkheidaanduiding' => Casts\Confidentiality::class,
        // 'betalingsindicatie'    => SomeClass::class,
        // 'betalingsindicatieWeergave'    => SomeClass::class,
        'laatsteBetaaldatum' => Casts\NullableDateTime::class,
        // 'zaakgeometrie' => SomeClass::class,
        // 'verlenging'    => SomeClass::class,
        // 'opschorting'   => SomeClass::class,
        // 'selectielijstklasse'   => SomeClass::class,
        'hoofdzaak' => Casts\Lazy\Zaak::class,
        'deelzaken' => Casts\Lazy\ZaakCollection::class,
        // 'relevanteAndereZaken'  => SomeClass::class,
        // 'eigenschappen' => SomeClass::class,
        'status' => Casts\Lazy\Status::class,
        // 'kenmerken' => SomeClass::class,
        // 'archiefnominatie'  => SomeClass::class,
        // 'archiefstatus' => SomeClass::class,
        'archiefactiedatum' => Casts\NullableDate::class,
        'resultaat' => Casts\Lazy\Resultaat::class,
        // 'opdrachtgevendeOrganisatie'    => SomeClass::class,
        'statussen' => Casts\Related\Statussen::class,
        'zaakinformatieobjecten' => Casts\Related\Zaakinformatieobjecten::class,
        'rollen' => Casts\Related\Rollen::class,
    ];

    /**
     * When the description is empty the 'Zaak' identification is returned.
     */
    public function title(): string
    {
        return $this->getValue('omschrijving', '') ?: $this->getValue('identificatie', '');
    }

    public function clarification(): string
    {
        return $this->getValue('toelichting', '');
    }

    public function identification(): string
    {
        return $this->getValue('identificatie', '');
    }

    public function permalink(): string
    {
        $identification = $this->getValue('identificatie', '');

        if ('' === $identification) {
            return '';
        }

        $supplier = $this->getSupplier();

        if ('' === $supplier) {
            return sprintf('%s/zaak/%s', get_home_url(), $this->encodeZaakIdentification($identification));
        }

        return sprintf('%s/zaak/%s/%s', get_home_url(), $this->encodeZaakIdentification($identification), $supplier);
    }

    public function getSupplier(): string
    {
        return $this->getValue('supplier', '');
    }

    public function steps(): array
    {
        if (! $this->steps instanceof Collection) {
            return [];
        }

        return (array) $this->steps->toArray();
    }

    public function statusHistory(): ?PagedCollection
    {
        return $this->getValue('status_history');
    }

    public function informationObjects(): ?Collection
    {
        return $this->getValue('information_objects');
    }

    public function hasNoStatus(): bool
    {
        return $this->statusExplanation() === 'Niet beschikbaar';
    }

    public function statusExplanation(): string
    {
        return $this->getValue('status_explanation', '');
    }

    public function result(): ?Resultaat
    {
        return $this->getValue('result');
    }

    public function resultExplanation(): string
    {
        return $this->result()->toelichting ?? '';
    }

    public function startDate(string $format = 'j F Y'): string
    {
        $startDate = $this->getValue('startdatum', null);

        if (! $startDate instanceof DateTimeImmutable) {
            return '';
        }

        return date_i18n($format, $startDate->getTimestamp());
    }

    public function registerDate(string $format = 'j F Y'): string
    {
        $registerDate = $this->getValue('registratiedatum', null);

        if (! $registerDate instanceof DateTimeImmutable) {
            return '';
        }

        return date_i18n($format, $registerDate->getTimestamp());
    }

    public function endDate(string $format = 'j F Y'): string
    {
        $startDate = $this->getValue('einddatum', null);

        if (! $startDate instanceof DateTimeImmutable) {
            return '';
        }

        return date_i18n($format, $startDate->getTimestamp());
    }

    public function hasEndDate(): bool
    {
        $endDate = $this->getValue('einddatum', null);

        return $endDate instanceof DateTimeImmutable;
    }

    public function zaaktypeDescription(): string
    {
        return $this->getValue('zaaktype_description', '');
    }

    public function endDatePlanned(string $format = 'j F Y')
    {
        $endDatePlanned = $this->getValue('einddatumGepland', null);

        if (! $endDatePlanned instanceof DateTimeImmutable) {
            return '';
        }

        return date_i18n($format, $endDatePlanned->getTimestamp());
    }

    /**
     * Wether or not the current Zaak is initiated by the given BSN.
     */
    public function isInitiatedBy(string $bsn): bool
    {
        $validRollen = $this->rollen->filter(function (Rol $rol) use ($bsn) {
            return $rol->isInitiator()
                && $rol->betrokkeneType->is('natuurlijk_persoon')
                && $rol->betrokkeneIdentificatie['inpBsn'] === $bsn;
        });

        return $validRollen->isNotEmpty();
    }
}
