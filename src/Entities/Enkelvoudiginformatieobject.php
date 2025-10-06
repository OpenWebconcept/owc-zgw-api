<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities;

use Exception;
use DateTimeImmutable;

use function OWC\ZGW\container;

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

    public function title(): string
    {
        return $this->getValue('titel', '');
    }

    public function fileName(): string
    {
        return $this->getValue('bestandsnaam', '');
    }

    public function content(): string
    {
        return $this->getValue('inhoud', '');
    }

    public function language(): string
    {
        return $this->getValue('taal', '');
    }

    public function sizeFormatted(): string
    {
        $size = $this->size();

        return $size ? size_format($size) : '';
    }

    public function size(): int
    {
        return $this->getValue('bestandsomvang', 0);
    }

    public function creationDate(): string
    {
        $date = $this->getValue('creatiedatum', null);

        if (! $date instanceof DateTimeImmutable) {
            return '';
        }

        try {
            return $date->format('d-m-Y');
        } catch (Exception $e) {
            return '';
        }
    }

    public function formatType(): string
    {
        $mimeType = $this->getValue('formaat', '');

        if (! is_string($mimeType) || 1 > strlen($mimeType)) {
            return '';
        }

        $mimeMap = container()->get('mime.mapping');

        return is_array($mimeMap) ? ($mimeMap[$mimeType] ?? '') : '';
    }

    public function formattedMetaData(): string
    {
        $meta = array_filter([
            $this->formatType(),
            $this->sizeFormatted(),
            $this->creationDate(),
        ]);

        if (empty($meta)) {
            return '';
        }

        return implode(', ', $meta);
    }

    public function downloadUrl(string $zaakIdentification, string $supplier): string
    {
        if ($this->isClassified() || ! $this->hasFinalStatus()) {
            return '';
        }

        $identification = $this->identification();

        if ('' === $identification || '' === $zaakIdentification || '' === $supplier) {
            return '';
        }

        return sprintf('%s/zaak-download/%s/%s/%s', get_home_url(), $identification, $this->encodeZaakIdentification($zaakIdentification), $supplier);
    }

    protected function identification(): string
    {
        $url = $this->url();

        if (empty($url)) {
            return '';
        }

        $parts = explode('/', $url);

        return end($parts) ?: '';
    }

    public function url(): string
    {
        return $this->getValue('url', '');
    }

    /**
     * @param string $attribute Either 'value' or 'label'
     */
    public function status(string $attribute = 'value'): string
    {
        return $this->getValue('status', null)?->$attribute ?? '';
    }

    public function hasFinalStatus(): bool
    {
        if ($this->hasReceiptDate()) {
            return true;
        }

        $status = $this->status();

        $finalStatusses = [
            'definitief',
            'gearchiveerd',
        ];

        return in_array($status, $finalStatusses);
    }

    public function hasReceiptDate(): bool
    {
        return ! empty($this->getValue('ontvangstdatum', ''));
    }

    /**
     * @param string $attribute Either 'value' or 'label'
     */
    public function confidentialityDesignation(string $attribute = 'value'): string
    {
        return $this->getValue('vertrouwelijkheidaanduiding', null)?->$attribute ?? '';
    }

    public function isCaseConfidential(): bool
    {
        $designation = $this->confidentialityDesignation();

        return 'zaakvertrouwelijk' === $designation;
    }

    public function isConfidential(): bool
    {
        $designation = $this->confidentialityDesignation();

        return 'vertrouwelijk' === $designation;
    }

    public function displayAllowedByConfidentialityDesignation(): bool
    {
        $designation = $this->confidentialityDesignation();

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
        $designation = $this->confidentialityDesignation();
        $classifiedDesignations = [
            'intern',
            'confidentieel',
            'geheim',
            'zeer_geheim',
        ];

        return in_array($designation, $classifiedDesignations);
    }
}
