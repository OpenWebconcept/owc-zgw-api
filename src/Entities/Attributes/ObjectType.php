<?php

declare(strict_types=1);

namespace OWC\ZGW\Entities\Attributes;

enum ObjectType: string
{
    case ADRES = 'adres';
    case BESLUIT = 'besluit';
    case BUURT = 'buurt';
    case ENKELVOUDIG_DOCUMENT = 'enkelvoudig_document';
    case GEMEENTE = 'gemeente';
    case GEMEENTELIJKE_OPENBARE_RUIMTE = 'gemeentelijke_openbare_ruimte';
    case HUISHOUDEN = 'huishouden';
    case INRICHTINGSELEMENT = 'inrichtingselement';
    case KADASTRALE_ONROERENDE_ZAAK = 'kadastrale_onroerende_zaak';
    case KUNSTWERKDEEL = 'kunstwerkdeel';
    case MAATSCHAPPELIJKE_ACTIVITEIT = 'maatschappelijke_activiteit';
    case MEDEWERKER = 'medewerker';
    case NATUURLIJK_PERSOON = 'natuurlijk_persoon';
    case NIET_NATUURLIJK_PERSOON = 'niet_natuurlijk_persoon';
    case OPENBARE_RUIMTE = 'openbare_ruimte';
    case ORGANISATORISCHE_EENHEID = 'organisatorische_eenheid';
    case PAND = 'pand';
    case SPOORBAANDEEL = 'spoorbaandeel';
    case STATUS = 'status';
    case TERREINDEEL = 'terreindeel';
    case TERREIN_GEBOUWD_OBJECT = 'terrein_gebouwd_object';
    case VESTIGING = 'vestiging';
    case WATERDEEL = 'waterdeel';
    case WEGDEEL = 'wegdeel';
    case WIJK = 'wijk';
    case WOONPLAATS = 'woonplaats';
    case WOZ_DEELOBJECT = 'woz_deelobject';
    case WOZ_OBJECT = 'woz_object';
    case WOZ_WAARDE = 'woz_waarde';
    case ZAKELIJK_RECHT = 'zakelijk_recht';
    case OVERIGE = 'overige';
}
