<?php

use App\Entity\Recrutador;
use App\Handler\RecrutadorUpdateHandler;
use App\Service\Entity\RecrutadorService;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;

class RecrutadorUpdateHandlerTest extends TestCase
{

    public function testUpdateRecrutadorInvalid()
    {
        $errorMessage = ['Erro: dados invalidos'];

        $recrutadorService = $this->createMock(RecrutadorService::class);
        $recrutadorService->method('update')
            ->willThrowException(new \Core\Exception\ValidationException($errorMessage, 400));


        $recrutador = new Recrutador(1);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('find')
            ->willReturn($recrutador);

        $recrutadorHandler = new RecrutadorUpdateHandler($recrutadorService, $entityManager);

        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withParsedBody([]);

        $response = $recrutadorHandler->handle($serverRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('400', $response->getStatusCode());

        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertEquals($errorMessage, $responseBody);
    }

    public function testUpdateRecrutadorThatDoesNotExists()
    {
        $recrutadorService = $this->createMock(RecrutadorService::class);

        $recrutador = new Recrutador(1);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('find')
            ->willReturn(null);

        $recrutadorHandler = new RecrutadorUpdateHandler($recrutadorService, $entityManager);

        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withParsedBody([]);

        $response = $recrutadorHandler->handle($serverRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('404', $response->getStatusCode());

        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertEquals(['Recrutador não pode ser editado, pois o mesno não está cadastrado'], $responseBody);
    }

    public function testUpdateRecrutadorValid()
    {

        $recrutador = new Recrutador(1);

        $recrutadorService = $this->createMock(RecrutadorService::class);
        $recrutadorService->method('update')
            ->willReturn($recrutador);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('find')
            ->willReturn($recrutador);

        $recrutadorHandler = new RecrutadorUpdateHandler($recrutadorService, $entityManager);

        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withParsedBody([]);

        $response = $recrutadorHandler->handle($serverRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('200', $response->getStatusCode());

        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertEquals(['id' => $recrutador->getId()], $responseBody);
    }
}
