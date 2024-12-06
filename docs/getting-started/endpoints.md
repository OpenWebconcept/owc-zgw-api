# Endpoints

The ZGW standard has several endpoints which can be accessed. This will show you how to access them. We'll be using OpenZaak with the 'zaken' endpoint in this example, but it can be exchanged with any of the supported ZGW clients or endpoints.

## Accessing endpoints

The easiest way to access any endpoint is through any Client instance. All (supported) endpoints are available as a method on the Client class. For example:
```php
use function OWC\ZGW\ApiClient;

$openzaakClient = apiClient('my-oz-client');

$zakenEndpoint = $openzaakClient->zaken();
```

### Available endpoints

At the time of writing, the following endpoints are available to most (but not all!) ZGW clients.

| 📥 ZRC | 🗂️ ZTC | 📄 DRC |
|--|--|--|
| zaken | zaaktypen | objectinformatieobjecten |
| statussen | statustypen | enkelvoudiginformatieobjecten |
| rollen | roltypen |  |
| resultaten | catalogussen |  |
| zaakeigenschappen | resultaattypen |  |
| zaakinformatieobjecten | informatieobjecttypen |  |
| zaakobjecten | eigenschappen |  |

All of these endpoints are callable as a method on your client. It will return an `OWC\ZGW\Endpoints\Endpoint` instance.

```php
$zakenEndpoint = $openzaakClient->zaaktypen();
$zakenEndpoint = $openzaakClient->objectinformatieobjecten();
$zakenEndpoint = $openzaakClient->catalogussen();
// etc.
```

### Endpoint support

Not every ZGW client supports every endpoint, so it's a good habit to check for its support:

```php
if ($openzaakClient->supports('zaken')) {
    // here be magic
}
```

## Requesting entities

Most endpoints support three basic operations: `all()`, `filter()` and `get()`.

### All entities

Request all entities through the `all()` method:

```php
$zakenEndpoint = $openzaakClient->zaken();

$zaken = $zakenEndpoint->all();
```

The result will be a `OWC\ZGW\Support\PagedCollection` [(read more about collections)](collections.md). 

### Single entity

Request a single entity through the `get()` method:

```php
$zakenEndpoint = $openzaakClient->zaken();

$zaak = $zakenEndpoint->get('unique-identifier-here');
```

The result will be a `OWC\ZGW\Entities\Entity` [(read more about entities)](entities.md).

### Filtering entities

Request entities which match a given filter. 

```php
$filteredZaken = $zakenEndpoint->filter($filter);
```

The `$filter` has to be of type `OWC\ZGW\Endpoints\Filter\AbstractFilter`. This will give any filter a few basic operations:

```php
use OWC\ZGW\Endpoints\Filter\ZakenFilter;

$filter = new ZakenFilter();

// Add any parameter
$filter->add('myparameter', 'myvalue');

// Check if it exists
$filter->has('myparameter', 'myvalue');

// Or remove it
$filter->remove('myparameter');

```

All endpoints have their own filter implementation with their own methods, in respect to that endpoint. For example the `ZakenFilter` will have a method to add a BSN-number filter:

```php
use OWC\ZGW\Endpoints\Filter\ZakenFilter;

$filter = new ZakenFilter();
$filter->byBsn('111222333');

$zakenByBsnNumber = $zakenEndpoint->filter($filter);
```

### Other methods

Other endpoints might support different methods. Check the [technical docs]() to see which endpoint supports what method.

## Collections and entities

Most operations on an endpoint either return an Entity or a Collection. 

- [Read more about Entities](entities.md)
- [Read more about Collections](collections.md)
