<?php namespace Codeacious\OAuth2Provider;

return [
    'service_manager' => [
        'factories' => [
            Config\ClientAssertionTypeFactory::class => Config\FactoryFactory::class,
            Config\GrantTypeFactory::class => Config\FactoryFactory::class,
            Config\ResponseTypeFactory::class => Config\FactoryFactory::class,
            Config\ScopeUtilFactory::class => Config\FactoryFactory::class,
            Config\ServerFactory::class => Config\FactoryFactory::class,
            Config\StorageFactory::class => Config\FactoryFactory::class,
            Config\TokenTypeFactory::class => Config\FactoryFactory::class,
        ],
    ],
];