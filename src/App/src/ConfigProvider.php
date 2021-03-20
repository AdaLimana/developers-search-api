<?php

declare(strict_types=1);

namespace App;

use App\Handler\Factory\RecrutadorCreateHandlerFactory;
use App\Handler\Factory\RecrutadorDeleteHandlerFactory;
use App\Handler\Factory\RecrutadorHandlerFactory;
use App\Handler\Factory\RecrutadorUpdateHandlerFactory;
use App\Handler\RecrutadorCreateHandler;
use App\Handler\RecrutadorDeleteHandler;
use App\Handler\RecrutadorHandler;
use App\Handler\RecrutadorUpdateHandler;
use App\Service\Entity\Factory\RecrutadorServiceFactory;
use App\Service\Entity\RecrutadorService;
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

                //Service/Entity
                RecrutadorService::class => RecrutadorServiceFactory::class,

                //Handler
                RecrutadorHandler::class        => RecrutadorHandlerFactory::class,
                RecrutadorCreateHandler::class  => RecrutadorCreateHandlerFactory::class,
                RecrutadorUpdateHandler::class  => RecrutadorUpdateHandlerFactory::class,
                RecrutadorDeleteHandler::class  => RecrutadorDeleteHandlerFactory::class,
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