# Clients

This package supports multiple ZGW 'clients'. A client represents the connection to a registry. For example: OpenZaak. It does not come with a pre-configured client. This will show you how to add and access one.

## Adding a client

Zaaksysteem clients are easily configured and resolved through the `OWC\ZGW\ApiClientManager` class. Every client requires two elements: a collection of URLs and some credentials. For both are designated classes available:

1. `OWC\ZGW\ApiUrlCollection`: contains URLs to the endpoints
2. `OWC\ZGW\ApiCredentials`: contains credentials and (optionally!) SSL certificates

> [!IMPORTANT]
> Decos JOIN has a custom implementation of the `ApiCredentials` class.

Add any Zaaksysteem client through the `addClient()` method on the `ApiClientManager`. It requires an unique name, a fully qualified class name and both `ApiUrlCollection` and `ApiCredentials` classes. For example:

```php
$manager = new \OWC\ZGW\ApiClientManager();

$uris = new \OWC\ZGW\ApiUrlCollection(/*...*/);
$uris->setZakenEndpoint('https://url.com/zaken/api/v1');
$uris->setCatalogiEndpoint('https://url.com/catalogi/api/v1');
$uris->setDocumentenEndpoint('https://url.com/documenten/api/v1');

$credentials = new \OWC\ZGW\ApiCredentials();
$credentials->setClientId('client_id');
$credentials->setClientSecret('client_secret');

$manager->addClient('my-oz-client', OWC\ZGW\Clients\OpenZaak\Client::class, $credentials, $uris);
```

## Accessing a client
After adding a client, you'll be able to access a fully built client through the `getClient(string $name)` method!

```php
// Resolve it with it's name
$client = $manager->getClient('my-oz-client');
```

## Using helper functions
The `ApiClientManager` class has some helper functions which can be accessed after initializing the class. These helper functions are within the `OWC\ZGW` namespace(!).

1. `container()`: returns the internal `DI\Container` instance
2. `resolve(string $name)`: quickly access any definition in the container
3. `apiClientManager()`: returns the `ApiClientManager` class
4. `apiClient(string $name)`: returns any defined client

### Build a client
The easiest way to build any configured zaaksysteem client:

```php
OWC\ZGW\apiClient(string $name): OWC\ZGW\Contracts\AbstractClient
```

All clients inherit from `OWC\ZGW\Contracts\AbstractClient`. 

### ApiClientManager access

```php
OWC\ZGW\apiClientManager(): OWC\ZGW\ApiClientManger
```

### Container access

Use the `container()` function to access the `DI\Container` instance. This container is used to easily resolve classes. 

```php
OWC\ZGW\container(): Di\Container
```

[View the documentation of DI\Container](https://php-di.org/doc/container.html)

## Endpoints

We've added our client(s). [Now lets access an endpoint](docs/endpoints.md)