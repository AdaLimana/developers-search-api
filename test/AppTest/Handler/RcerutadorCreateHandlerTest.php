<?php

use App\Entity\Recrutador;
use App\Handler\RecrutadorCreateHandler;
use App\Service\Entity\RecrutadorService;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;

class RecrutadorCreateHandlerTest extends TestCase
{

    public function testCreateRecrutadorInvalid()
    {

        $errorMessage = ['Erro: dados invalidos'];

        $recrutadorService = $this->createMock(RecrutadorService::class);
        $recrutadorService->method('create')
                           ->willThrowException(new \Core\Exception\ValidationException($errorMessage, 400));

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $recrutadorHandler = new RecrutadorCreateHandler($recrutadorService, $entityManager);

        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withParsedBody([]);
        
        $response = $recrutadorHandler->handle($serverRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('400', $response->getStatusCode());

        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertEquals($errorMessage, $responseBody);
    }

    public function testCreateRecrutadorValid()
    {

        $recrutador = new Recrutador(1);

        $recrutadorService = $this->createMock(RecrutadorService::class);
        $recrutadorService->method('create')
                           ->willReturn($recrutador);

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $recrutadorHandler = new RecrutadorCreateHandler($recrutadorService, $entityManager);

        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withParsedBody([]);
        
        $response = $recrutadorHandler->handle($serverRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('201', $response->getStatusCode());

        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertEquals(['id' => $recrutador->getId()], $responseBody);
    }
}