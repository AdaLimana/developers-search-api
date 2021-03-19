<?php

declare(strict_types=1);

namespace App;

use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.laminas.dev/laminas-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'doctrine' => $this->getEntityConfig()
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
            ],
            'factories'  => [
            ],
        ];
    }

    /**
     *
     * Retorna a configuracao para mapear as entidades
     */
    public function getEntityConfig()
    {
        return [
            'driver' => [
                __NAMESPACE__ . "_driver" => [
                    'class' => AnnotationDriver::class,
                    'cache' => 'array',
                    'paths' => [__DIR__ . '/../src/Entity']
                ],
                'orm_default' => [
                    'drivers' => [
                        __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                    ],
                ]
            ]
        ];
    }
}