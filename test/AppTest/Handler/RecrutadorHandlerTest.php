<?php

use App\Handler\RecrutadorHandler;
use App\Service\Entity\RecrutadorService;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;

class RecrutadorHandlerTest extends TestCase
{

    public function testGetRecrutadorThatDoesNotExists()
    {

        $recrutadorService = $this->createMock(RecrutadorService::class);
        $recrutadorService->method('get')
                           ->with(1)
                           ->willThrowException(new \Exception('Error, Recrutador não encontrado', 404));

        $recrutadorHandler = new RecrutadorHandler($recrutadorService);

        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('id', 1);
        
        $response = $recrutadorHandler->handle($serverRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('404', $response->getStatusCode());

        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertIsArray($responseBody);
    }

    public function testGetRecrutador()
    {
        $recrutador = [
            'email' => 'beltrado@dominio.com'
        ];

        $recrutadorService = $this->createMock(RecrutadorService::class);
        $recrutadorService->method('get')
                           ->with(1)
                           ->willReturn($recrutador);

        $recrutadorHandler = new RecrutadorHandler($recrutadorService);

        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('id', 1);
        
        $response = $recrutadorHandler->handle($serverRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('200', $response->getStatusCode());

        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertIsArray($responseBody);
        $this->assertEquals($recrutador, $responseBody);
    }

    public function testGetListEmpty()
    {

        $recrutadores = [
            'data' => [],
            'totalRecords' => 0
        ];

        $recrutadorService = $this->createMock(RecrutadorService::class);
        $recrutadorService->method('getList')
                           ->willReturn($recrutadores);

        $recrutadorHandler = new RecrutadorHandler($recrutadorService);

        $serverRequest = new ServerRequest();
        
        $response = $recrutadorHandler->handle($serverRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('200', $response->getStatusCode());

        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertIsArray($responseBody);
        $this->assertEquals($recrutadores, $responseBody);
    }

    public function testGetList()
    {

        $recrutadores = [
            'data' => [
                [
                    'id' => 1,
                    'email' => 'joao@dominio.com'
                ],
                [
                    'id' => 2,
                    'email' => 'pedro@dominio.com'
                ],
            ],
            'totalRecords' => 2
        ];

        $recrutadorService = $this->createMock(RecrutadorService::class);
        $recrutadorService->method('getList')
                           ->willReturn($recrutadores);

        $recrutadorHandler = new RecrutadorHandler($recrutadorService);

        $serverRequest = new ServerRequest();
        
        $response = $recrutadorHandler->handle($serverRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('200', $response->getStatusCode());

        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertIsArray($responseBody);
        $this->assertEquals($recrutadores, $responseBody);
    }


}