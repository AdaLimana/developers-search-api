<?php

declare(strict_types=1);

namespace App\Handler\Factory;

use App\Handler\CandidatoHandler;
use App\Service\Entity\CandidatoService;
use Psr\Container\ContainerInterface;

class CandidatoHandlerFactory
{
    public function __invoke(ContainerInterface $container) : CandidatoHandler
    {
        return new CandidatoHandler($container->get(CandidatoService::class));
    }
}
