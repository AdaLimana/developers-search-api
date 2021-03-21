<?php

declare(strict_types=1);

namespace App\Handler;

use App\Entity\Candidato;
use App\Service\Entity\CandidatoService;
use Core\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CandidatoUpdateHandler implements RequestHandlerInterface
{

    private CandidatoService $candidatoService;
    private EntityManagerInterface $entityManager;

    public function __construct(CandidatoService $candidatoService, EntityManagerInterface $entityManager)
    {
        $this->candidatoService = $candidatoService;
        $this->entityManager = $entityManager;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        try{
            
            $candidato = $this->getCandidato($request->getAttribute('id'));

            $data = $request->getParsedBody();
            
            $candidato = $this->candidatoService->update($candidato, $data);
            
            $this->entityManager->persist($candidato);
            $this->entityManager->flush();
            
            return new JsonResponse(['id' => $candidato->getId()], 200);
            
        } 
        catch (ValidationException $ex) {
            return new JsonResponse(
                    json_decode($ex->getMessage()),
                    $ex->getCode() ?: 400
            );
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
            throw new \Exception('Candidato não pode ser editado, pois o mesno não está cadastrado', 404);
        }

        return $candidato;
    }
}