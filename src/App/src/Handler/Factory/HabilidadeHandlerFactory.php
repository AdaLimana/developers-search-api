<?php

declare(strict_types=1);

namespace App\Handler\Factory;

use App\Handler\HabilidadeHandler;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class HabilidadeHandlerFactory
{
    public function __invoke(ContainerInterface $container) : HabilidadeHandler
    {
        return new HabilidadeHandler($container->get(EntityManagerInterface::class));
    }
}
