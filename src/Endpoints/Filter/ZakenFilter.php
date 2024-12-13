<?php

namespace OWC\ZGW\Endpoints\Filter;

use DateTimeInterface;
use OWC\ZGW\Entities\Zaaktype;

class ZakenFilter extends AbstractFilter
{
    // identificatie
    // bronorganisatie
    // archiefnominatie
    // archiefnominatie__in
    // archiefactiedatum
    // archiefactiedatum__lt
    // archiefactiedatum__gt
    // archiefstatus
    // archiefstatus__in
    // rol__betrokkeneType
    // rol__betrokkene
    // rol__omschrijvingGeneriek
    // maximaleVertrouwelijkheidaanduiding
    // rol__betrokkeneIdentificatie__natuurlijkPersoon__inpBsn
    // rol__betrokkeneIdentificatie__medewerker__identificatie
    // rol__betrokkeneIdentificatie__organisatorischeEenheid__identificatie
    // ordering

    public function byZaaktype(Zaaktype $zaaktype): parent
    {
        return $this->add('zaaktype', $zaaktype->url);
    }

    public function orderBy(string $orderBy): parent
    {
        /**
         * Might be used in other places, in that case
         * move to config/container.
         */
        $orderByParams = [
            'startdatum',
            'einddatum',
            'publicatiedatum',
            'archiefactiedatum',
            'registratiedatum',
            'identificatie',
        ];

        if (! in_array($orderBy, $orderByParams)) {
            return $this;
        }

        return $this->add('ordering', $orderBy);
    }

    public function byZaaktypeIdentification(Zaaktype $zaaktype): parent
    {
        return $this->add('identificatie', $zaaktype->identificatie);
    }

    public function byBsn(string $bsn): parent
    {
        return $this->add(
            'rol__betrokkeneIdentificatie__natuurlijkPersoon__inpBsn',
            $bsn
        );
    }

    public function byStartDate(DateTimeInterface $startDate, string $operator = self::OPERATOR_EQUALS): parent
    {
        return $this->addDateFilter('startdatum', $startDate, $operator, 'Y-m-d');
    }

    public function byArchiveActionDate(DateTimeInterface $endDate, string $operator = self::OPERATOR_EQUALS): parent
    {
        return $this->addDateFilter('archiefactiedatum', $endDate, $operator, 'Y-m-d');
    }
}
