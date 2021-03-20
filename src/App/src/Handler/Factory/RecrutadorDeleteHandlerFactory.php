<?php

declare(strict_types=1);

namespace App\Handler\Factory;

use App\Handler\RecrutadorDeleteHandler;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class RecrutadorDeleteHandlerFactory
{
    public function __invoke(ContainerInterface $container) : RecrutadorDeleteHandler
    {
        return new RecrutadorDeleteHandler(
            $container->get(EntityManagerInterface::class)
        );
    }
}
