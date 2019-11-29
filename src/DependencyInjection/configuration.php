<?php

declare(strict_types=1);

namespace Console\DependencyInjection;

use Console\Command\UpdateIPCommand;
use Console\DigitalOcean\DOClient;
use Console\DigitalOcean\DOClientFactory;
use Console\IP\IPResolver;
use Console\IP\IPUpdater;
use Console\IP\Resolvers\V4Resolver;
use Console\IP\Resolvers\V6Resolver;
use DI;
use GuzzleHttp\Client;
use Symfony\Component\Console\Application;

return [
    Application::class => DI\create(Application::class)
        ->method('add', DI\get(UpdateIPCommand::class)),

    UpdateIPCommand::class => DI\create(UpdateIPCommand::class)->constructor(DI\get(IPUpdater::class)),

    IPUpdater::class => DI\create(IPUpdater::class)->constructor(DI\get(IPResolver::class), DI\get(DOClient::class)),

    DOClient::class => DI\factory([new DOClientFactory, 'create']),

    IPResolver::class => DI\create(IPResolver::class)->constructor(DI\get('app.resolvers')),

    'app.resolvers' => [
        DI\create(V4Resolver::class)->constructor(Di\get('app.ipv4_client')),
        DI\create(V6Resolver::class)->constructor(DI\get('app.ipv6_client')),
    ],

    'app.ipv4_client' => DI\factory(static function () {
        return new Client([
            'base_uri' => 'http://checkip.dyndns.org',
            'timeout'  => 2.0,
        ]);
    }),

    'app.ipv6_client' => DI\factory(static function () {
        return new Client([
            'base_uri' => 'http://checkipv6.dyndns.org',
            'timeout'  => 2.0,
        ]);
    }),
];
