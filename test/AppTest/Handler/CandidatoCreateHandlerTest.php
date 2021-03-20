<?php

use App\Entity\Candidato;
use App\Handler\CandidatoCreateHandler;
use App\Service\Entity\CandidatoService;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;

class CandidatoCreateHandlerTest extends TestCase
{

    public function testCreateCandidatoInvalid()
    {

        $errorMessage = ['Erro: dados invalidos'];

        $candidatoService = $this->createMock(CandidatoService::class);
        $candidatoService->method('create')
                           ->willThrowException(new \Core\Exception\ValidationException($errorMessage, 400));

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $candidatoHandler = new CandidatoCreateHandler($candidatoService, $entityManager);

        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withParsedBody([]);
        
        $response = $candidatoHandler->handle($serverRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('400', $response->getStatusCode());

        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertEquals($errorMessage, $responseBody);
    }

    public function testCreateCandidatoValid()
    {

        $candidato = new Candidato(1);

        $candidatoService = $this->createMock(CandidatoService::class);
        $candidatoService->method('create')
                           ->willReturn($candidato);

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $candidatoHandler = new CandidatoCreateHandler($candidatoService, $entityManager);

        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withParsedBody([]);
        
        $response = $candidatoHandler->handle($serverRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('201', $response->getStatusCode());

        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertEquals(['id' => $candidato->getId()], $responseBody);
    }
}