# Documentation

This PHP packages offers an implementation of the 'Zaakgericht Werken' (ZGW) API's, in accordance with the [Gemma standards](https://vng-realisatie.github.io/gemma-zaken/standaard/). The library enables developers to easily integrate and access the ZGW API's of various registries.

## Registry support

| Client | 📥 ZRC | 🗂️ ZTC | 📄 DRC | 🔐 Auth |
|--|--|--|--|--|
| OpenZaak | ✅ `1.5.1` | ✅ `1.5.1` | ✅ `1.5.1` | JWT tokens |
| RxMission | ✅ `1.5.1` | ✅ `1.5.1` | ✅ `1.5.1` | JWT tokens |
| XXLLNC | ✅ `unkown version` | ✅ `unkown version` | ✅ `1.5.1` | Pre-distributed API key |
| DecosJoin | unknown | unknown | unknown | unknown |
| Procura | unknown | unknown | unknown |unknown |

## Before you start

At a high level, this package can be broken down in three elements:

1. Clients: they represent the connection to a registry. For example: OpenZaak.
2. Endpoints: an endpoint within a component. For example: Zaken in the ZRC component or Enkelvoudiginformatieobjecten in the DRC component.
3. Entities: typed objects of data, usually returned from the API. For example a 'Zaak' or Objectinformatie.

Good to know: names of most endpoints and entities are not translated. Some classes therefore have names that is a mix of Dutch and English. 

In most cases you'd:
1. Resolve your client of your choice (e.g. OpenZaak)
2. Access the endpoint you need through the client (e.g. Zaken)
3. Request the entity of your choice through the endpoint (e.g. Zaak)

## Usuage

1. [Setup a client](docs/clients.md)
2. [Access an endpoint](docs/endpoints.md)
3. [Work with entities](docs/entities.md)
4. [Work with collections](docs/collections.md)
