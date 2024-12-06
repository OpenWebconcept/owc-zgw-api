# Entities

- [Catalogus](#Catalogus) 
- [Eigenschap](#Eigenschap) 
- [Enkelvoudiginformatieobject](#Enkelvoudiginformatieobject) 
- [Entity](#Entity) 
- [Informatieobjecttype](#Informatieobjecttype) 
- [Objectinformatie](#Objectinformatie) 
- [Resultaat](#Resultaat) 
- [Resultaattype](#Resultaattype) 
- [Rol](#Rol) 
- [Roltype](#Roltype) 
- [Status](#Status) 
- [Statustype](#Statustype) 
- [Zaak](#Zaak) 
- [Zaakeigenschap](#Zaakeigenschap) 
- [Zaakinformatieobject](#Zaakinformatieobject) 
- [Zaakobject](#Zaakobject) 
- [Zaaktype](#Zaaktype)

## Catalogus

## Eigenschap

## Enkelvoudiginformatieobject

## Entity

## Informatieobjecttype

## Objectinformatie

## Resultaat

## Resultaattype

## Rol

## Roltype

## Status

## Statustype

## Zaak

Namespace: `OWC\ZGW\Entities\Zaak`

### Methods

| Method | Description | Parameters | Returns |
|--|--|--|--|--|
| `permalink()` | Returns the WordPress permalink to this entity. | none | `string` |
| `isInitiatedBy($bsn)` | Wether or not the current Zaak is initiated by the given BSN. | `<string> $bsn` | `bool` |

### Properties

| Property | Type |
|--|--|
| url | `?string` |
| uuid | `?string` |
| identificatie | `?string` |
| bronorganisatie | `?string` |
| omschrijving | `?string` |
| toelichting | `?string` |
| zaaktype | `?OWC\ZGW\Entities\Zaaktype` |
| registratiedatum | `?DateTimeImmutable` |
| verantwoordelijkeOrganisatie | `?string` |
| startdatum | `?DateTimeImmutable` |
| einddatum | `?DateTimeImmutable` |
| einddatumGepland | `?DateTimeImmutable` |
| uiterlijkeEinddatumAfdoening | `?DateTimeImmutable` |
| publicatiedatum | `?DateTimeImmutable` |
| communicatiekanaal | `?string` |
| productenOfDiensten | `?string` |
| vertrouwelijkheidaanduiding | `OWC\ZGW\Entities\Attributes\Confidentiality` |
| betalingsindicatie | `?string` |
| betalingsindicatieWeergave | `?string` |
| laatsteBetaaldatum | `?DateTimeImmutable` |
| zaakgeometrie | `?string` |
| verlenging | `?string` |
| opschorting | `?string` |
| selectielijstklasse | `?string` |
| hoofdzaak | `?OWC\ZGW\Entities\Zaak` |
| deelzaken | `?OWC\ZGW\Support\Collection` |
| relevanteAndereZaken | `?string` |
| eigenschappen | `?string` |
| status | `?OWC\ZGW\Entities\Status` |
| kenmerken | `?string` |
| archiefnominatie | `?string` |
| archiefstatus | `?string` |
| archiefactiedatum | `?DateTimeImmutable` |
| resultaat | `?OWC\ZGW\Entities\Resultaat` |
| opdrachtgevendeOrganisatie | `?string` |
| statussen | `?OWC\ZGW\Support\Collection` |
| zaakinformatieobjecten | `?OWC\ZGW\Support\Collection` |
| rollen | `?OWC\ZGW\Support\Collection` |

## Zaakeigenschap

## Zaakinformatieobject

## Zaakobject

## Zaaktype
