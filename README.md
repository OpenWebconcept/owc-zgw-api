# ZGW API for PHP and WordPress

This PHP packages offers an implementation of the 'Zaakgericht Werken' (ZGW) API's, in accordance with the [Gemma standards](https://vng-realisatie.github.io/gemma-zaken/standaard/). The library enables developers to easily integrate and access the ZGW API's of various registries.

## Registry support

| Client | 📥 ZRC | 🗂️ ZTC | 📄 DRC | 🔐 Auth |
|--|--|--|--|--|
| OpenZaak | ✅ `1.5.1` | ✅ `1.5.1` | ✅ `1.5.1` | JWT tokens |
| RxMission | ✅ `1.5.1` | ✅ `1.5.1` | ✅ `1.5.1` | JWT tokens |
| XXLLNC | ✅ `unkown version` | ✅ `unkown version` | ✅ `1.5.1` | Pre-distributed API key |
| DecosJoin | unknown | unknown | unknown | unknown |
| Procura | unknown | unknown | unknown |unknown |

## Installation

Via Composer:

```sh
composer require owc/zgw-api
```

## Usage

[Please read the docs](docs/index.md).

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Breaking changes

There are breaking changes compared to the [Zaaksysteem plugin](https://github.com/openwebconcept/plugin-owc-gravityforms-zaaksysteem).

[View breaking changes](docs/breaking-changes.md).