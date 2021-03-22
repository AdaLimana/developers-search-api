<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Authentication\UserInterface;
use Mezzio\Session\SessionMiddleware;

class IsAdminMiddleware implements MiddlewareInterface
{

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
        
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);

        $recrutador = $session->get(UserInterface::class);
        
        $id = $request->getAttribute('id');

        if($recrutador['details']['id'] != 1 && $recrutador['details']['id'] != $id ){

            return new JsonResponse(["Você não tem permissão para executar essa ação"], 403);
        }
        
        return $handler->handle($request);
    }
}
