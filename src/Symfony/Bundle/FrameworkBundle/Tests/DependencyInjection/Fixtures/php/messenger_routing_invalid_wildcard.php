<?php

$container->loadFromExtension('framework', [
    'annotations' => false,
    'http_method_override' => false,
    'serializer' => true,
    'messenger' => [
        'serializer' => [
            'default_serializer' => 'messenger.transport.symfony_serializer',
        ],
        'routing' => [
            'Symfony\*\DummyMessage' => ['audit'],
        ],
        'transports' => [
            'audit' => 'null://',
        ],
    ],
]);
