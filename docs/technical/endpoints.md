# Endpoints

Available endpoints.

- [CatalogussenEndpoint](#CatalogussenEndpoint) 
- [EigenschappenEndpoint](#EigenschappenEndpoint) 
- [EnkelvoudiginformatieobjectenEndpoint](#EnkelvoudiginformatieobjectenEndpoint) 
- [InformatieobjecttypenEndpoint](#InformatieobjecttypenEndpoint) 
- [ObjectinformatieEndpoint](#ObjectinformatieEndpoint) 
- [ResultaattypenEndpoint](#ResultaattypenEndpoint) 
- [ResultatenEndpoint](#ResultatenEndpoint) 
- [RollenEndpoint](#RollenEndpoint) 
- [RoltypenEndpoint](#RoltypenEndpoint) 
- [StatussenEndpoint](#StatussenEndpoint) 
- [StatustypenEndpoint](#StatustypenEndpoint) 
- [ZaakeigenschappenEndpoint](#ZaakeigenschappenEndpoint) 
- [ZaakinformatieobjectenEndpoint](#ZaakinformatieobjectenEndpoint) 
- [ZaakobjectenEndpoint](#ZaakobjectenEndpoint) 
- [ZaaktypenEndpoint](#ZaaktypenEndpoint) 
- [ZakenEndpoint](#ZakenEndpoint)

## CatalogussenEndpoint

## EigenschappenEndpoint

## EnkelvoudiginformatieobjectenEndpoint

## InformatieobjecttypenEndpoint

## ObjectinformatieEndpoint

## ResultaattypenEndpoint

## ResultatenEndpoint

## RollenEndpoint

## RoltypenEndpoint

## StatussenEndpoint

## StatustypenEndpoint

## ZaakeigenschappenEndpoint

## ZaakinformatieobjectenEndpoint

## ZaakobjectenEndpoint

## ZaaktypenEndpoint

## ZakenEndpoint

| Method | Description | Parameters | Returns |
|--|--|--|--|
| `all()` | Returns all available `Zaak` entities. | none | [`PagedCollection`](../getting-started/collections.md) |
| `get($identifier)` | Returns all single `Zaak` entity. | `<string> $identifier` | [`?Zaak`](../technical/entities.md) |
| `filter($filter)` | Request `Zaak` entities which match the given filter. | `<ZakenFilter> $filter` | [`PagedCollection`](../getting-started/collections.md) |
| `create($model)` | Create a new `Zaak` in the registry. | `<Zaak> $model` | [`Zaak`](../technical/entities.md) |
| `delete($identifier)` | Delete a `Zaak` from the registry. | `<string> $identifier` | `Response` |
