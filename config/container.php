<?php

declare(strict_types=1);

namespace OWC\ZGW;

use DI\Container;

/**
 * Link interfaces to their concretions.
 */

return [
    'api.endpoints' => function () {
        return new Support\Collection([]);
    },

    /**
     * OpenZaak
     */
    Clients\OpenZaak\Client::class => function (
        Container $container,
        ApiCredentials $credentials,
        ApiUrlCollection $endpoints
    ) {
        return new Clients\OpenZaak\Client(
            $container->make('http.client'),
            $container->make(Clients\OpenZaak\Authenticator::class, compact('credentials')),
            $endpoints
        );
    },

    /**
     * RxMission
     */
    Clients\RxMission\Client::class => function (
        Container $container,
        ApiCredentials $credentials,
        ApiUrlCollection $endpoints
    ) {
        return new Clients\RxMission\Client(
            $container->make('http.client'),
            $container->make(Clients\RxMission\Authenticator::class, compact('credentials')),
            $endpoints
        );
    },

    /**
     * Decos JOIN
     */
    Clients\DecosJoin\Client::class => function (
        Container $container,
        ApiCredentials $credentials,
        ApiUrlCollection $endpoints
    ) {
        return new Clients\DecosJoin\Client(
            $container->make('http.client'),
            $container->make(Clients\DecosJoin\Authenticator::class, compact('credentials')),
            $endpoints
        );
    },

    /**
     * XXLLNC
     */
    Clients\Xxllnc\Client::class => function (
        Container $container,
        ApiCredentials $credentials,
        ApiUrlCollection $endpoints
    ) {
        return new Clients\Xxllnc\Client(
            $container->make('http.client'),
            $container->make(Clients\Xxllnc\Authenticator::class, compact('credentials')),
            $endpoints
        );
    },

    /**
     * Procura
     */
    Clients\Procura\Client::class => function (
        Container $container,
        ApiCredentials $credentials,
        ApiUrlCollection $endpoints
    ) {
        $authentication = $container->make(
            Clients\Procura\Authenticator::class,
            compact('credentials')
        );

        if (! $authentication->hasCertificates()) {
            throw new \InvalidArgumentException('Missing SSL certificates: the Procura client requires additional SSL certificates.');
        }

        return new Clients\Procura\Client(
            $container->make('http.client'),
            $authentication,
            $endpoints
        );
    },

    /**
     * HTTP clients
     */
    'http.client' => function (Container $container) {
        return $container->get(Http\WordPress\WordPressRequestClient::class);
    } ,
    Http\WordPress\WordPressRequestClient::class => function () {
        return new Http\WordPress\WordPressRequestClient(
            new Http\RequestOptions([
                'timeout' => 15,
                'headers' => [
                    'Accept-Crs' => 'EPSG:4326',
                    'Content-Crs' => 'EPSG:4326',
                    'Content-Type' => 'application/json',
                ],
            ])
        );
    },
    Http\Curl\CurlRequestClient::class => function () {
        return new Http\Curl\CurlRequestClient(
            new Http\RequestOptions([
                'timeout' => 15,
                'headers' => [
                    'Accept-Crs' => 'EPSG:4326',
                    'Content-Crs' => 'EPSG:4326',
                    'Content-Type' => 'application/json',
                ],
            ])
        );
    },

    /**
     * HTTP Message logging
     */
    'message.logger.active' => false,
    'message.logger.detail' => Http\Logger\MessageDetail::BLACK_BOX,
    'message.logger.path' => dirname(__DIR__) . '/owc-http-messages.json',
    'message.logger' => function (Container $container) {
        $logger = new \Monolog\Logger('owc_http_log');

        $handler = new \Monolog\Handler\StreamHandler(
            $container->get('message.logger.path'),
            \Monolog\Logger::DEBUG
        );

        $handler->setFormatter(new \Monolog\Formatter\JsonFormatter());
        $logger->pushHandler($handler);

        $logger->pushProcessor(new \Monolog\Processor\WebProcessor());
        $logger->pushProcessor(new Http\Logger\LogDetailProcessor($container->get('message.logger.detail')));
        $logger->pushProcessor(new Http\Logger\FilterBsnProcessor());

        return $logger;
    },
];
