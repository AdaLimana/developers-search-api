<?php

use App\Entity\Candidato;
use App\Handler\CandidatoDeleteHandler;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;

class CandidatoDeleteHandlerTest extends TestCase
{

    public function testUpdateCandidatoThatDoesNotExists()
    {

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('find')
                      ->willReturn(null);

        $candidatoHandler = new CandidatoDeleteHandler($entityManager);

        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('id', 1);

        $response = $candidatoHandler->handle($serverRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('404', $response->getStatusCode());

        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertEquals(['Candidato não pode ser excluído, pois o mesno não está cadastrado'], $responseBody);
    }

    public function testDeleteCandidato()
    {

        $candidato = new Candidato(1);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('find')
            ->willReturn($candidato);

        $candidatoHandler = new CandidatoDeleteHandler($entityManager);

        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withParsedBody([]);

        $response = $candidatoHandler->handle($serverRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('200', $response->getStatusCode());

        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertEquals(['Candidato excluído com sucesso'], $responseBody);
    }
}
