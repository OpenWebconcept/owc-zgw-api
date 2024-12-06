# Entities

Data returned by an endpoint is wrapped in an `Entity` class. If there are multiple entities, a `Collection` of entities is returned.

## Accessing properties

All entities are derived from the `OWC\ZGW\Entities\Entity` class. This class stores all properties in an internal array. These properties are accessible:

```php
$zaak = $zakenEndpoint->get('aabbbccddeeff');

var_dump($zaak->omschrijving); // (string) 'aabbbcc'
```

Please view the [Gemma standard](https://vng-realisatie.github.io/gemma-zaken/standaard/zaken/#releases) to see which properties are available. All property names are equal to the standard.

## Castable properties

All properties can be made 'castable'. This means that any value on the Entity can be cast (changed, updated) when setting, getting or serializing that value on the Entity. For example: all date related properties on a `OWC\ZGW\Entities\Zaak` model (e.g. registratiedatum, startdatum, einddatum) are automatically cast to a `DateTimeImmutable` instance. This allows easy formatting when displaying the value or comparing it to other `DateTimeInterface` instances.

```php
$zaak = $zakenEndpoint->get('aabbbccddeeff');

var_dump($zaak->startdatum->format('Y-m-d')); // (string) '2024-11-11'
```

Most properties can be `null`. Make sure to check the value beforehand:
```php
// PHP 7.4 and down
if ($zaak->startdatum) {
    echo $zaak->startdatum->format('Y-m-d');
}

// PHP 8.0 and up
echo $zaak->startdatum?->format('Y-m-d');
```

## Defined castable properties

All castable properties are defined in the entity class. The property `$casts` stores them in an array. The key is the property name, it's value a qualified class name of the class doing the casting. This class has to either extend `OWC\ZGW\Entities\Casts\AbstractCast` or implement the `OWC\ZGW\Entities\Casts\CastsAttributes` interface.

For example: in the `Zaak` entity, a `Casts\NullableDate` caster has been defined for the `registratiedatum` property. 

```php
<?php

// Code omitted for clarity

class Zaak extends Entity
{
    protected array $casts = [
        'registratiedatum' => Casts\NullableDate::class,
    ];

    //...
}
```

The `NullableDate` makes sure the value is cast to a `DateTimeImmutable` instance whenever it is accessed if it has a valid date value. If not, it's value will be `null`. 

## Lazy loadable entities

Most entities have connected entities. For example: a Zaak has a Zaaktype attached to it. When a Zaak has been resolved through the ZGW API, it usually* has an URL reference to the connected Zaaktype:

```json
{
  "identificatie": "string",
  "bronorganisatie": "string",
  "omschrijving": "string",
  "toelichting": "string",

  "zaaktype": "http://example.com/zaken/api/v1/aaabbbbbcccc",
  // data omitted for clarity
}
```

These connected entities are not automatically loaded. Only when accessed the entity is resolved through the API.

```php
// We'll start with finding a specific zaak.
$zaak = $zakenEndpoint->get('aabbbccddeeff');


// The $zaak has references to zaaktype, deelzaken, status, resultaat, etc. At
// this point in time, the $zaak Entity only has an URL reference. However, 
// accessing the attribute causes the plugin to load the actual resource.

$zaaktype = $zaak->zaaktype; // Causes a HTTP request.

// The $zaaktype also has reference to multiple entities. The same thing will
// happen here: accessing the attribute will make the plugin load the full
// resource from the API and return an Entity instance of that resource. 

$statustypen = $zaaktype->statustypen; // Causes a HTTP request.

// In this case, $statustypen is a OWC\ZGW\Support\Collection 
// which can be looped over and has additional helper methods.

foreach ($statustypen->sortByAttribute('volgnummer') as $statustype) {
    echo $statustype->omschrijving;
}
```

Additional calls to loaded entities will not cause additional HTTP requests. The loaded resource is set on the Entity model.

## Expand

In some cases it might be more efficient to preload connected resources. Since ZGW version 1.5.0 this is possible on select endpoints. Not all connected resources can be expanded.

To expand a resource, either specify which resources to expand or to exclude from expansion:

```php
$zaak = $client->zaken()->expandExcept(['status.statustype'])->get('aaabbbbccc');
$zaak = $client->zaken()->expandOnly(['zaaktype'])->get('aaabbbbccc');
```

It's also possible to disable expand completely or enable it for all possible resources:
```php
$zaak = $client->zaken()->expandNone()->get('aaabbbbccc');

$zaak = $client->zaken()->expandAll()->get('aaabbbbccc');
```

> [!IMPORTANT]
> If expand is enabled, it will try to expand the 'zaaktype' and 'status' by default.

### Supported expandable resources

#### Zaken endpoint

1.5.0
- zaaktype
- status
- status.statustype
- hoofdzaak.status.statustype

1.5.1
- deelzaken
- deelzaken.resultaat
- deelzaken.resultaat.resultaattype
- deelzaken.rollen
- deelzaken.rollen.roltype
- deelzaken.status
- deelzaken.status.statustype
- deelzaken.zaakinformatieobjecten
- deelzaken.zaakobjecten
- deelzaken.zaaktype
- eigenschappen
- eigenschappen.eigenschap
- hoofdzaak
- hoofdzaak.resultaat
- hoofdzaak.resultaat.resultaattype
- hoofdzaak.rollen
- hoofdzaak.rollen.roltype
- hoofdzaak.status
- hoofdzaak.status.statustype
- hoofdzaak.zaakinformatieobjecten
- hoofdzaak.zaakobjecten
- hoofdzaak.zaaktype
- resultaat
- resultaat.resultaattype
- rollen
- rollen.roltype
- status
- status.statustype
- zaakinformatieobjecten
- zaakobjecten
- zaaktype
