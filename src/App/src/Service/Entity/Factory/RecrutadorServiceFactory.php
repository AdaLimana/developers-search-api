<?php

declare(strict_types=1);

namespace App\Service\Entity\Factory;

use App\Service\Entity\RecrutadorService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

class RecrutadorServiceFactory
{
    public function __invoke(ContainerInterface $container) : RecrutadorService
    {
        return new RecrutadorService($container->get(EntityManagerInterface::class));
    }
}
