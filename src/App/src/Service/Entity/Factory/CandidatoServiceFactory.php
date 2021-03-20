<?php

declare(strict_types=1);

namespace App\Service\Entity\Factory;

use App\Service\Entity\CandidatoService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class CandidatoServiceFactory
{
    public function __invoke(ContainerInterface $container) : CandidatoService
    {
        return new CandidatoService($container->get(EntityManagerInterface::class));
    }
}
