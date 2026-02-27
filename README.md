# ZGW API for PHP and WordPress

This PHP packages offers an implementation of the 'Zaakgericht Werken' (ZGW) API's, in accordance with the [Gemma standards](https://vng-realisatie.github.io/gemma-zaken/standaard/). The library enables developers to easily integrate and access the ZGW API's of various registries.

## Registry support

| Client    | 📥 ZRC             | 🗂️ ZTC            | 📄 DRC    | 🔐 Auth    |
|-----------|--------------------|--------------------|-----------|------------|
| OpenZaak  | ✅ `1.5.1`          | ✅ `1.5.1`          | ✅ `1.5.1` | JWT tokens |
| RxMission | ✅ `1.5.1`          | ✅ `1.5.1`          | ✅ `1.5.1` | JWT tokens |
| XXLLNC    | ✅ `unkown version` | ✅ `unkown version` | ✅ `1.5.1` | JWT tokens |
| DecosJoin | unknown            | unknown            | unknown   | unknown    |
| Procura   | unknown            | unknown            | unknown   | unknown    |
| OpenWave  | unknown            | unknown            | unknown   | unknown    |
| Mozart    | unknown            | unknown            | unknown   | unknown    |

## Installation

Use [composer](https://getcomposer.org/) to install this package.

```sh
composer require owc/zgw-api
```

> [!IMPORTANT]
> Version 2 of this package requires PHP >= 8.2.

## Usage

Read the documentation on [how to get started](docs/getting-started/index.md).

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Breaking changes

### Version 1

There are breaking changes compared to the [Zaaksysteem plugin](https://github.com/openwebconcept/plugin-owc-gravityforms-zaaksysteem). [View breaking changes](docs/breaking-changes.md).

### Version 2

The package requires PHP >= 8.2.