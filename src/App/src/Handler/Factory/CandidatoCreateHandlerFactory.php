<?php

declare(strict_types=1);

namespace App\Handler\Factory;

use App\Handler\CandidatoCreateHandler;
use App\Service\Entity\CandidatoService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class CandidatoCreateHandlerFactory
{
    public function __invoke(ContainerInterface $container) : CandidatoCreateHandler
    {
        return new CandidatoCreateHandler(
            $container->get(CandidatoService::class),
            $container->get(EntityManagerInterface::class)
        );
    }
}
