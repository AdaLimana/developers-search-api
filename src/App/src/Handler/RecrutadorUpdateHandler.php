<?php

declare(strict_types=1);

namespace App\Handler;

use App\Entity\Recrutador;
use App\Service\Entity\RecrutadorService;
use Core\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RecrutadorUpdateHandler implements RequestHandlerInterface
{

    private RecrutadorService $recrutadorService;
    private EntityManagerInterface $entityManager;

    public function __construct(RecrutadorService $recrutadorService, EntityManagerInterface $entityManager)
    {
        $this->recrutadorService = $recrutadorService;
        $this->entityManager = $entityManager;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        try{
            
            $recrutador = $this->getRecrutador($request->getAttribute('id'));

            $data = $request->getParsedBody();
            
            $recrutador = $this->recrutadorService->update($recrutador, $data);
            
            $this->entityManager->persist($recrutador);
            $this->entityManager->flush();
            
            return new JsonResponse(['id' => $recrutador->getId()], 200);
            
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
     * @return Recrutador
     * @throws \Exception
     */
    private function getRecrutador($id): Recrutador
    {
        $recrutador = $this->entityManager->find(Recrutador::class, $id);

        if(!$recrutador instanceof Recrutador){
            throw new \Exception('Recrutador não pode ser editado, pois o mesno não está cadastrado', 404);
        }

        return $recrutador;
    }
}