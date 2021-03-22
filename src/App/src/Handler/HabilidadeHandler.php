<?php

declare(strict_types=1);

namespace App\Handler;

use App\Entity\Habilidade;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class HabilidadeHandler implements RequestHandlerInterface
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {

        try{

            $result = $this->entityManager
                            ->createQueryBuilder()
                            ->select('habilidade')
                            ->from(Habilidade::class, 'habilidade')
                            ->getQuery()
                            ->getArrayResult();

            return new JsonResponse($result, 200);
        }
        catch (\Exception $ex) {   
            return new JsonResponse(
                    [$ex->getMessage()],
                    $ex->getCode() ?: 500
            );
        }   

    }
}