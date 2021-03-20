<?php

namespace App\Service\Entity;

use App\Entity\Candidato;
use App\Entity\Habilidade;
use Core\PHPUnit\TestCaseService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class CandidatoServiceTest extends TestCaseService
{

    private CandidatoService $candidatoService;

    protected function setUp(): void
    {

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $candidato = new Candidato();
        $candidatoRepository = $this->createMock(ObjectRepository::class);
        $candidatoRepository->expects($this->any())
            ->method('find')
            ->willReturn($candidato);

        $entityManager->expects($this->any())
                      ->method('getRepository')
                      ->willReturn($candidatoRepository);

        $this->candidatoService = new CandidatoService($entityManager);

    }

    public function testCreateWithAllPossibleData()
    {

        $data = [
            'email' => 'fulano@dominio.com',
            'nome' => 'beltrano',
            'idade' => 27,
            'habilidades' => [new Habilidade(), new Habilidade()],
            'linkedin' => 'https://linkedin.com'
        ];

        $candidato = $this->candidatoService->create($data);

        $this->assertInstanceOf(Candidato::class, $candidato);
        $this->assertInstanceOf(\DateTime::class, $candidato->getCreated());
        $this->assertNull($candidato->getUpdated());
        $this->assertEntityData($candidato, $data);
    }

    public function testCreateOnlyWithRequiredData()
    {
        $data = [
            'email' => 'fulano@dominio.com',
            'nome' => 'beltrano',
            'idade' => 27,
            'habilidades' => [new Habilidade(), new Habilidade()],
        ];

        $candidato = $this->candidatoService->create($data);

        $this->assertInstanceOf(Candidato::class, $candidato);
        $this->assertInstanceOf(\DateTime::class, $candidato->getCreated());
        $this->assertNull($candidato->getUpdated());
        $this->assertEntityData($candidato, $data);
    }

    public function testUpdateWithAllPossibleData()
    {
        $candidato = new Candidato(1);

        $data = [
            'email' => 'fulano@dominio.com',
            'nome' => 'beltrano',
            'idade' => 27,
            'habilidades' => [new Habilidade(), new Habilidade()],
            'linkedin' => 'https://linkedin.com'
        ];

        $candidato = $this->candidatoService->update($candidato, $data);

        $this->assertInstanceOf(Candidato::class, $candidato);
        $this->assertInstanceOf(\DateTime::class, $candidato->getUpdated());
        $this->assertEntityData($candidato, $data);
    }

    public function testUpdateonlyWithRequiredData()
    {
        $candidato = new Candidato(1);
        
        $data = [
            'email' => 'fulano@dominio.com',
            'nome' => 'beltrano',
            'idade' => 27,
            'habilidades' => [new Habilidade(), new Habilidade()],  
        ];

        $candidato = $this->candidatoService->update($candidato, $data);

        $this->assertInstanceOf(Candidato::class, $candidato);
        $this->assertInstanceOf(\DateTime::class, $candidato->getUpdated());
        $this->assertEntityData($candidato, $data, ['password']);
    }

}