<?php

declare(strict_types=1);

namespace App\Handler\Factory;

use App\Handler\CandidatoDeleteHandler;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class CandidatoDeleteHandlerFactory
{
    public function __invoke(ContainerInterface $container) : CandidatoDeleteHandler
    {
        return new CandidatoDeleteHandler($container->get(EntityManagerInterface::class));
    }
}
