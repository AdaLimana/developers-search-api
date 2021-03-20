<?php

declare(strict_types=1);

namespace App\Handler;

use App\Service\Entity\RecrutadorService;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RecrutadorHandler implements RequestHandlerInterface
{

    private RecrutadorService $recrutadorService;

    public function __construct(RecrutadorService $recrutadorService)
    {
        $this->recrutadorService = $recrutadorService;
    }

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {

        try{

            $id = $request->getAttribute('id');

            if(isset($id)){
                $result = $this->recrutadorService->get($id);
            }
            else{
                $parameters = $request->getQueryParams();
                $result = $this->recrutadorService->getList($parameters);
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