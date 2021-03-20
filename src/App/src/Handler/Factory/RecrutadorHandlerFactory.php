<?php

declare(strict_types=1);

namespace App\Handler\Factory;

use App\Handler\RecrutadorHandler;
use App\Service\Entity\RecrutadorService;
use Psr\Container\ContainerInterface;

class RecrutadorHandlerFactory
{
    public function __invoke(ContainerInterface $container) : RecrutadorHandler
    {
        return new RecrutadorHandler(
            $container->get(RecrutadorService::class)
        );
    }
}
