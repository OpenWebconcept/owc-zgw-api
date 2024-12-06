# Breaking changes

There are breaking changes compared to the [Zaaksysteem plugin](https://github.com/openwebconcept/plugin-owc-gravityforms-zaaksysteem).

## Removed default client names

Clients no longer have a default name or abbreviation. Instead, a name is given when registering a client with the `ApiClientManager`. This allows for connections to multiple identical clients.

## Removed client actions

Creating and deleting a `Zaak` or other 'actions' are removed from this package.

## Procura client SSL certificates
The Procura client requires additional SSL certificates. Make sure to add them to the `ApiCredentials` instance. For example:
```php
use OWC\ZGW\Clients\Procura\Client as Procura;

$procuraUris = new ApiUrlCollection(/* ... */);

$procuraCredentials = new ApiCredentials();
$procuraCredentials->setClientId('procura');
$procuraCredentials->setClientSecret('password');

$procuraCredentials->setPublicCertificate('/path/to/public.key');
$procuraCredentials->setPrivateCertificate('/path/to/private.key');

$manager->addClient('procura', Procura::class, $procuraCredentials, $procuraUris);
```

## Removed default properties on `Zaak` entity
The `Zaak` entity no longer has the following properties by default:

- leverancier
- steps
- status_history
- information_objects
- status_explanation
- result
- image
- zaaktype_description

## Removed 'downloadUrl' method on Enkelvoudiginformatieobject

This should be implemented in the application using this package.

## Entities require the full ZGW client

Before, all/most entities required the client (pretty) name for initialization. The constructor of those entities now require a `Client` entity.
