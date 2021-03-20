<?php

declare(strict_types=1);

namespace App\Handler;

use App\Service\Entity\CandidatoService;
use Core\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CandidatoCreateHandler implements RequestHandlerInterface
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
            
            $data = $request->getParsedBody();
            
            $candidato = $this->candidatoService->create($data);
            
            $this->entityManager->persist($candidato);
            $this->entityManager->flush();
            
            return new JsonResponse(['id' => $candidato->getId()], 201);
            
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
}