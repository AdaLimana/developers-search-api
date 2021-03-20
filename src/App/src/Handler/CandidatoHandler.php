<?php

declare(strict_types=1);

namespace App\Handler;

use App\Service\Entity\CandidatoService;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CandidatoHandler implements RequestHandlerInterface
{

    private CandidatoService $candidatoService;

    public function __construct(CandidatoService $candidatoService)
    {
        $this->candidatoService = $candidatoService;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {

        try{

            $id = $request->getAttribute('id');

            if(isset($id)){
                $result = $this->candidatoService->get($id);
            }
            else{
                $parameters = $request->getQueryParams();
                $result = $this->candidatoService->getList($parameters);
            }

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