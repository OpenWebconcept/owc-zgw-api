# Clients

This package supports multiple ZGW 'clients'. A client represents the connection to a registry. For example: OpenZaak. It does not come with a pre-configured client. This will show you how to add and access one.

## Adding a client

Zaaksysteem clients are easily configured and resolved through the `OWC\ZGW\ApiClientManager` class. Every client requires two elements: a collection of URLs and some credentials. For both are designated classes available:

1. `OWC\ZGW\ApiUrlCollection`: contains URLs to the endpoints
2. `OWC\ZGW\ApiCredentials`: contains credentials and (optionally!) SSL certificates

> [!IMPORTANT]
> Decos JOIN has a custom implementation of the `ApiCredentials` class. Use the `OWC\ZGW\Clients\DecosJoin\ApiCredentials` class to correctly set the client secret for the ZRC component.

Add any Zaaksysteem client through the `addClient()` method on the `ApiClientManager`. It requires:

- a unique name, e.g. 'my-oz-client',
- a fully qualified class name of the client implementation,
- the `ApiUrlCollection` instance,
- and the `ApiCredentials` instance.

```php
use OWC\ZGW\ApiCredentials;
use OWC\ZGW\ApiClientManager;
use OWC\ZGW\ApiUrlCollection;

$manager = new ApiClientManager();

$uris = new ApiUrlCollection();
$uris->setApiVersion('1.5.0');
$uris->setZakenEndpoint('https://url.com/zaken/api/v1');
$uris->setCatalogiEndpoint('https://url.com/catalogi/api/v1');
$uris->setDocumentenEndpoint('https://url.com/documenten/api/v1');

$credentials = new ApiCredentials();
$credentials->setClientId('client_id');
$credentials->setClientSecret('client_secret');

$manager->addClient('my-oz-client', OWC\ZGW\Clients\OpenZaak\Client::class, $credentials, $uris);
```

## Accessing a client
After adding a client, you'll be able to access a fully built client through the `getClient(string $name)` method!

```php
// Resolve it by it's name
$client = $manager->getClient('my-oz-client');
```

## Using helper functions
The `ApiClientManager` class has some helper functions which can be accessed after initializing the class. These helper functions are within the `OWC\ZGW` namespace.

1. `apiClient(string $name)`: returns any defined client
2. `apiClientManager()`: returns the `ApiClientManager` class
3. `resolve(string $name)`: quickly access any definition in the container
4. `container()`: returns the internal `DI\Container` instance

### Build a client
The easiest way to build any configured zaaksysteem client:

```php
use function OWC\ZGW\apiClient;

apiClient(string $name): OWC\ZGW\Contracts\AbstractClient
```

All clients inherit from `OWC\ZGW\Contracts\AbstractClient`. 

### ApiClientManager access

```php
use function OWC\ZGW\apiClientManager;

apiClientManager(): OWC\ZGW\ApiClientManger
```

### Container access

Use the `container()` function to access the `DI\Container` instance. This container is used to easily resolve classes. 

```php
use function OWC\ZGW\container;

container(): Di\Container
```

[View the documentation of the PHP-DI container.](https://php-di.org/doc/container.html)

## WordPress

This package offers a WordPress settings page integration. With it, clients can be configured by filling in some fields. [View the WordPress documentation](wordpress.md).

## Endpoints

We've added our client(s). [Now lets access an endpoint](endpoints.md)