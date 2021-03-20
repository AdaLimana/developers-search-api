<?php

declare(strict_types=1);

namespace App\Handler\Factory;

use App\Handler\RecrutadorUpdateHandler;
use App\Service\Entity\RecrutadorService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class RecrutadorUpdateHandlerFactory
{
    public function __invoke(ContainerInterface $container) : RecrutadorUpdateHandler
    {
        return new RecrutadorUpdateHandler(
            $container->get(RecrutadorService::class),
            $container->get(EntityManagerInterface::class)
        );
    }
}
