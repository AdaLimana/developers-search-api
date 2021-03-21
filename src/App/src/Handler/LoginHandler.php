<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Diactoros\Response\JsonResponse;
use Mezzio\Authentication\Session\PhpSession;
use Mezzio\Authentication\UserInterface;
use Mezzio\Session\SessionMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class LoginHandler implements RequestHandlerInterface
{
    
    private PhpSession $phpSession;
    
    public function __construct(PhpSession $phpSession) 
    {
        $this->phpSession = $phpSession;
    }
    
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        
        /*
         * Cada vez que receber uma requisição de login limpará a sessão atual
         * e uma nova sessão será criada para a autenticação.
         */
        $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
        if( $session->has(UserInterface::class)) {
            $session->clear();
        }
        
        $recrutador = $this->phpSession->authenticate($request) ?? null;

        if ($recrutador) {
            $recrutadorId = $recrutador->getDetails() ?$recrutador->getDetails()['id'] :null;
            return new JsonResponse(["id" => $recrutadorId], 200);
        }
        else {
            return new JsonResponse("Email ou senha inválida", 403);
        }
        
    }
}