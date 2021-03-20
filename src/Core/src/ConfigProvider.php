<?php

declare(strict_types=1);

namespace Core;

use Core\Validator\DoctrineValidator\Factory\ObjectExistsFactory;
use Core\Validator\DoctrineValidator\Factory\UniqueObjectFactory;
use Core\Validator\DoctrineValidator\ObjectExists;
use Core\Validator\DoctrineValidator\UniqueObject;

/**
 * The configuration provider for the Core module
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
            'dependencies' => $this->getDependencies()
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

                ObjectExists::class     => ObjectExistsFactory::class,
                UniqueObject::class     => UniqueObjectFactory::class   
            ],
        ];
    }
}