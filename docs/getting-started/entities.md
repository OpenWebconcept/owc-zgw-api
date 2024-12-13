# Entities

Data from an endpoint is wrapped in its own entity class (e.g. `Zaak`, `Zaaktype`, `Informatieobjecttype`, etc.). All entities are derived from the `OWC\ZGW\Entities\Entity` class. This way accessing data from a `Zaak` entity works the same as with any other entity.

## Available data

View the [technical documentation](../technical/entities.md) (WIP) to see which properties are available. Alternatively, use the [Gemma standard](https://vng-realisatie.github.io/gemma-zaken/standaard/zaken/#releases) documentation. All property names are equal to the standard.

## Accessing data

All data is exposed through class properties and can be accessed as such.

```php
$zaak = $zakenEndpoint->get('aabbbccddeeff');

var_dump($zaak->omschrijving); // (string) 'aabbbcc'
```

Alternatively use the `getValue(string $name, $default = null)` method. It alows for a default return value if the property is not available.

```php
var_dump($zaak->getValue('this-does-not-exist', false)); // (boolean) false
```

Use the `getAttributeValue(string $name, $default = null)` method if you want to access the _raw_ value, without casting applied (see below).

```php
var_dump($zaak->getAttributeValue('zaaktype')); // (string) 'aaaaa-bbbbb-ccccc'
```

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

View the [technical documentation](../technical/entities.md) (WIP) to see which properties are casted to different data types.

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

## Adding functionality

All entities support custom functionality through macros. This makes it easy to add additional methods to entities at run time. A macro is a closure that will be executed when it's called. The closure can access the entity through `$this` just like any other method.

```php
use OWC\ZGW\Entities\Zaak;

Zaak::macro('permalink', function () {
    return sprintf(
        '%s/zaak/%s',
        get_site_url(),
        $this->getValue('identificatie', '')
    );
});

var_dump($zaak->permalink()); // (string) 'https://...'
```

### Macro parameters

If needed, additional parameters are also supported:

```php
Zaak::macro('isRegisteredBefore', function (DateTime $date) {
    $registrationDate = $this->registratiedatum;
    if (! $registrationDate) {
        return false;
    }

    return $registrationDate < $date;
});

$date = new DateTime('2012-12-12');

var_dump($zaak->isRegisteredBefore($date));
```

### Static macros

In some cases a static method may be prefered. For example when creating a new entity from a specific set of data. Adding one works exactly the same as a 'normal' macro. The difference is that the closure has no access to `$this` in a static context. 

```php
use OWC\ZGW\Entities\Zaak;
use OWC\ZGW\Contracts\Client;

Zaak::macro('fromSpecificData', function (array $data, Client $client) {
    // Do some form of preparation here, for example:
    $zaakData = [
        'bronorganisatie' => 'Foo Bar',
        'zaaktype' => $data['field.4.1'],
        'verantwoordelijkeOrganisatie' => 'Baz Foo',
        // More data preparation...
    ];

    return new Zaak($zaakData, $client);
});

$zaak = Zaak::fromSpecificData(
    ['field.4.1' => 'asdfasdf'],
    apiClientManager()->getClient('my-registered-client')
);
```

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
