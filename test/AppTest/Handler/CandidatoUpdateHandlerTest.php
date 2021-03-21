<?php

use App\Entity\Candidato;
use App\Handler\CandidatoUpdateHandler;
use App\Service\Entity\CandidatoService;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;

class CandidatoUpdateHandlerTest extends TestCase
{

    public function testUpdateCandidatoInvalid()
    {
        $errorMessage = ['Erro: dados invalidos'];

        $candidatoService = $this->createMock(CandidatoService::class);
        $candidatoService->method('update')
            ->willThrowException(new \Core\Exception\ValidationException($errorMessage, 400));


        $candidato = new Candidato(1);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('find')
            ->willReturn($candidato);

        $candidatoHandler = new CandidatoUpdateHandler($candidatoService, $entityManager);

        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withParsedBody([]);

        $response = $candidatoHandler->handle($serverRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('400', $response->getStatusCode());

        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertEquals($errorMessage, $responseBody);
    }

    public function testUpdateCandidatoThatDoesNotExists()
    {
        $candidatoService = $this->createMock(CandidatoService::class);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('find')
            ->willReturn(null);

        $candidatoHandler = new CandidatoUpdateHandler($candidatoService, $entityManager);

        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withParsedBody([]);

        $response = $candidatoHandler->handle($serverRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('404', $response->getStatusCode());

        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertEquals(['Candidato não pode ser editado, pois o mesno não está cadastrado'], $responseBody);
    }

    public function testUpdateCandidatoValid()
    {

        $candidato = new Candidato(1);

        $candidatoService = $this->createMock(CandidatoService::class);
        $candidatoService->method('update')
            ->willReturn($candidato);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('find')
            ->willReturn($candidato);

        $candidatoHandler = new CandidatoUpdateHandler($candidatoService, $entityManager);

        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withParsedBody([]);

        $response = $candidatoHandler->handle($serverRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('200', $response->getStatusCode());

        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertEquals(['id' => $candidato->getId()], $responseBody);
    }
}
