<?php

declare(strict_types=1);

namespace App;

use App\Handler\CandidatoCreateHandler;
use App\Handler\CandidatoDeleteHandler;
use App\Handler\CandidatoHandler;
use App\Handler\CandidatoUpdateHandler;
use App\Handler\Factory\CandidatoCreateHandlerFactory;
use App\Handler\Factory\CandidatoDeleteHandlerFactory;
use App\Handler\Factory\CandidatoHandlerFactory;
use App\Handler\Factory\CandidatoUpdateHandlerFactory;
use App\Handler\Factory\HabilidadeHandlerFactory;
use App\Handler\Factory\LoginHandlerFactory;
use App\Handler\Factory\RecrutadorCreateHandlerFactory;
use App\Handler\Factory\RecrutadorDeleteHandlerFactory;
use App\Handler\Factory\RecrutadorHandlerFactory;
use App\Handler\Factory\RecrutadorUpdateHandlerFactory;
use App\Handler\HabilidadeHandler;
use App\Handler\LoginHandler;
use App\Handler\LogoutHandler;
use App\Handler\RecrutadorCreateHandler;
use App\Handler\RecrutadorDeleteHandler;
use App\Handler\RecrutadorHandler;
use App\Handler\RecrutadorSessionHandler;
use App\Handler\RecrutadorUpdateHandler;
use App\Handler\WithoutSessionHandler;
use App\Service\Entity\CandidatoService;
use App\Service\Entity\Factory\CandidatoServiceFactory;
use App\Service\Entity\Factory\RecrutadorServiceFactory;
use App\Service\Entity\RecrutadorService;
use App\Middleware\IsAdminMiddleware;
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

                //Handler
                WithoutSessionHandler::class,
                RecrutadorSessionHandler::class,
                LogoutHandler::class,

                //Middleware
                IsAdminMiddleware::class

            ],
            'factories'  => [

                //Service/Entity
                RecrutadorService::class    => RecrutadorServiceFactory::class,
                CandidatoService::class     => CandidatoServiceFactory::class,

                //Handler
                LoginHandler::class =>  LoginHandlerFactory::class,

                RecrutadorHandler::class        => RecrutadorHandlerFactory::class,
                RecrutadorCreateHandler::class  => RecrutadorCreateHandlerFactory::class,
                RecrutadorUpdateHandler::class  => RecrutadorUpdateHandlerFactory::class,
                RecrutadorDeleteHandler::class  => RecrutadorDeleteHandlerFactory::class,

                CandidatoHandler::class         => CandidatoHandlerFactory::class,
                CandidatoCreateHandler::class   => CandidatoCreateHandlerFactory::class,
                CandidatoUpdateHandler::class   => CandidatoUpdateHandlerFactory::class,
                CandidatoDeleteHandler::class   => CandidatoDeleteHandlerFactory::class,

                HabilidadeHandler::class => HabilidadeHandlerFactory::class,

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