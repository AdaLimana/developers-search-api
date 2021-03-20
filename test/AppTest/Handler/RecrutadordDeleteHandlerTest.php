<?php

use App\Entity\Recrutador;
use App\Handler\RecrutadorDeleteHandler;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;

class RecrutadorDeleteHandlerTest extends TestCase
{

    public function testUpdateRecrutadorThatDoesNotExists()
    {

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('find')
                      ->willReturn(null);

        $recrutadorHandler = new RecrutadorDeleteHandler($entityManager);

        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withAttribute('id', 1);

        $response = $recrutadorHandler->handle($serverRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('404', $response->getStatusCode());

        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertEquals(['Recrutador não pode ser excluído, pois o mesno não está cadastrado'], $responseBody);
    }

    public function testDeleteRecrutadorValid()
    {

        $recrutador = new Recrutador(1);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('find')
            ->willReturn($recrutador);

        $recrutadorHandler = new RecrutadorDeleteHandler($entityManager);

        $serverRequest = new ServerRequest();
        $serverRequest = $serverRequest->withParsedBody([]);

        $response = $recrutadorHandler->handle($serverRequest);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals('200', $response->getStatusCode());

        $responseBody = json_decode((string) $response->getBody(), true);
        $this->assertEquals(['Recrutador excluído com sucesso'], $responseBody);
    }
}
