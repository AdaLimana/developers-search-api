<?php

declare(strict_types=1);

namespace App\Handler\Factory;

use App\Handler\RecrutadorCreateHandler;
use App\Service\Entity\RecrutadorService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class RecrutadorCreateHandlerFactory
{
    public function __invoke(ContainerInterface $container) : RecrutadorCreateHandler
    {
        return new RecrutadorCreateHandler(
            $container->get(RecrutadorService::class),
            $container->get(EntityManagerInterface::class)
        );
    }
}
