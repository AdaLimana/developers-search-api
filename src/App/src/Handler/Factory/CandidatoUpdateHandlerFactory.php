<?php

declare(strict_types=1);

namespace App\Handler\Factory;

use App\Handler\CandidatoUpdateHandler;
use App\Service\Entity\CandidatoService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class CandidatoUpdateHandlerFactory
{
    public function __invoke(ContainerInterface $container) : CandidatoUpdateHandler
    {
        return new CandidatoUpdateHandler(
            $container->get(CandidatoService::class),
            $container->get(EntityManagerInterface::class)
        );
    }
}
