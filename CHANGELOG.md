# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

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
