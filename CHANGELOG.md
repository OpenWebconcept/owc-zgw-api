# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.5.1] - 2026-04-16

### Fixed

- ResultatenEndpoint now available for Decos client

## [2.5.0] - 2026-03-20

### Added

- Support for mTLS
- Support for creation of Zaakobjecten

## [2.4.0] - 2026-02-27

### Added

- Support for the Mozart client

## [2.3.0] - 2026-02-24

### Fixed

- Certificates are now correctly applied to clients

### Added

- Support for the OpenWave client

## [2.2.2] - 2026-02-18

### Fixed

- PHP JWT security issues #20

### Added

- 'Next' step state for zaken

## [2.2.1] - 2026-01-29

### Fixed

- Drop support for PHP 8.1 as it is no longer supported
- Update dependencies

## [2.2.0] - 2026-01-26

### Added

- Support for (SSL) certificates for all clients

### Fixed

- A bug where all characters were allowed in register names, resulting in errors in URLs, rewrite rules or other situations where only a restricted character set is supported.
- A bug where an invalid URL whas created when deleting zaken
- A bug where invalid data is sent when creating zaakinformatieobjecten

## [2.1.0] - 2025-10-15

### Added

- Added helper methods to the `Zaak`, `ZaakEigenschap`, `Statustype` and `Enkelvoudiginformatieobject` entities
- Added the 'steps' attribute to the `Zaak` entity

### Fixed

- Fixed Decos Join authentication for the ZRC registry

## [2.0.0] - 2025-08-22

### Breaking

- This package now requires PHP 8.1
- The XXLLNC client now uses JWT tokens instead of pre-generated API keys

### Fixed

- Naming conflicts with WordPress core
- Error handling of retrieving single entity resources

## [1.0.1] - 2025-03-17

### Fixed

- Function with void return type must not return a value

## [1.0.0] - 2024-10-11

### Breaking

- Removed default client names and the possibility to resolve them as such
- Removed client actions
- Procura client SSL certificates should now be added to the `ApiCredentials` instance
- Removed various properties on the `Zaak` entity which were outside of the Gemma standards
- Removed 'downloadUrl' and various other methods on `Enkelvoudiginformatieobject`
- Entities (e.g. `Zaak` or `Zaaktype`) now require a full `Client` instance

### Added

- Resolve connected resources across ZGW clients. A connected resource (e.g. zaak -> zaaktype) will now be properly resolved even if it's within a different ZGW registry. This works as long as a ZGW client is properly configured for the registry.
- Usage of multiple instances of the same client (but on different endpoints) is now possible

### Fixed

- Loads of bugs

### Removed

- @see Breaking
