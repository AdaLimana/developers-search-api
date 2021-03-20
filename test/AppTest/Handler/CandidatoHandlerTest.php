<?php

use App\Handler\CandidatoHandler;
use App\Service\Entity\CandidatoService;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;

class CandidatoHandlerTest extends TestCase
{

    public function testGetCandidatoThatDoesNotExists()
    {

        $candidatoService = $this->createMock(CandidatoService::class);
        $candidatoService->method('get')
                           ->with(1)
                           ->willThrowException(new \Exception('Error, Candidato não encontrado', 404));

        $candidatoHandler = new CandidatoHandler($candidatoService);

        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('id', 1);
        
        $response = $candidatoHandler->handle($serverRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('404', $response->getStatusCode());

        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertIsArray($responseBody);
    }

    public function testGetCandidato()
    {
        $candidato = [
            'email' => 'beltrado@dominio.com',
            'nome'  => 'paulo',
            'idade' => 12,
            'habilidades' => [
                [
                    'id' => 1,
                    'name' => 'C'
                ]
            ]
        ];

        $candidatoService = $this->createMock(CandidatoService::class);
        $candidatoService->method('get')
                           ->with(1)
                           ->willReturn($candidato);

        $candidatoHandler = new CandidatoHandler($candidatoService);

        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('id', 1);
        
        $response = $candidatoHandler->handle($serverRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('200', $response->getStatusCode());

        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertIsArray($responseBody);
        $this->assertEquals($candidato, $responseBody);
    }

    public function testGetListEmpty()
    {

        $candidatos = [
            'data' => [],
            'totalRecords' => 0
        ];

        $candidatoService = $this->createMock(CandidatoService::class);
        $candidatoService->method('getList')
                           ->willReturn($candidatos);

        $candidatoHandler = new CandidatoHandler($candidatoService);

        $serverRequest = new ServerRequest();
        
        $response = $candidatoHandler->handle($serverRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('200', $response->getStatusCode());

        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertIsArray($responseBody);
        $this->assertEquals($candidatos, $responseBody);
    }

    public function testGetList()
    {

        $candidatos = [
            'data' => [
                [
                    'id'    => 1,
                    'email' => 'beltrado@dominio.com',
                    'nome'  => 'paulo',
                    'idade' => 40,
                    'habilidades' => [
                        [
                            'id' => 1,
                            'name' => 'C'
                        ]
                    ]
                ],
                [
                    'id'    => 2,
                    'email' => 'fulano@dominio.com',
                    'nome'  => 'fulano',
                    'idade' => 30,
                    'habilidades' => [
                        [
                            'id' => 1,
                            'name' => 'C'
                        ]
                    ]
                ],
            ],
            'totalRecords' => 2
        ];

        $candidatoService = $this->createMock(CandidatoService::class);
        $candidatoService->method('getList')
                           ->willReturn($candidatos);

        $candidatoHandler = new CandidatoHandler($candidatoService);

        $serverRequest = new ServerRequest();
        
        $response = $candidatoHandler->handle($serverRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('200', $response->getStatusCode());

        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertIsArray($responseBody);
        $this->assertEquals($candidatos, $responseBody);
    }


}