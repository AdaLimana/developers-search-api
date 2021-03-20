<?php

declare(strict_types=1);

namespace App\Handler;

use App\Entity\Recrutador;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RecrutadorDeleteHandler implements RequestHandlerInterface
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        try{
            
            $recrutador = $this->getRecrutador($request->getAttribute('id'));

            $this->entityManager->remove($recrutador);
            $this->entityManager->flush();
            
            return new JsonResponse(['Recrutador excluído com sucesso'], 200);            
        }
        catch (\Exception $ex) {
            return new JsonResponse(
                    [$ex->getMessage()],
                    $ex->getCode() ?: 500
            );
        }       
    }

    /**
     * @param $id
     * @return Recrutador
     * @throws \Exception
     */
    private function getRecrutador($id): Recrutador
    {
        $recrutador = $this->entityManager->find(Recrutador::class, $id);

        if(!$recrutador instanceof Recrutador){
            throw new \Exception('Recrutador não pode ser excluído, pois o mesno não está cadastrado', 404);
        }

        return $recrutador;
    }
}