<?php

declare(strict_types=1);

namespace App\Handler;

use App\Entity\Candidato;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CandidatoDeleteHandler implements RequestHandlerInterface
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        try{
            
            $candidato = $this->getCandidato($request->getAttribute('id'));

            $this->entityManager->remove($candidato);
            $this->entityManager->flush();
            
            return new JsonResponse(['Candidato excluído com sucesso'], 200);            
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
     * @return Candidato
     * @throws \Exception
     */
    private function getCandidato($id): Candidato
    {
        $candidato = $this->entityManager->find(Candidato::class, $id);

        if(!$candidato instanceof Candidato){
            throw new \Exception('Candidato não pode ser excluído, pois o mesno não está cadastrado', 404);
        }

        return $candidato;
    }
}