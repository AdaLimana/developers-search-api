<?php

namespace App\Service\Entity;

use App\Entity\Recrutador;
use Core\PHPUnit\TestCaseService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Laminas\Crypt\Password\Bcrypt;

class RecrutadorServiceTest extends TestCaseService
{

    private RecrutadorService $recrutadorService;

    protected function setUp(): void
    {

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $recrutador = new Recrutador();
        $recrutadorRepository = $this->createMock(ObjectRepository::class);
        $recrutadorRepository->expects($this->any())
            ->method('find')
            ->willReturn($recrutador);

        $entityManager->expects($this->any())
                      ->method('getRepository')
                      ->willReturn($recrutadorRepository);

        $this->recrutadorService = new RecrutadorService($entityManager);

    }

    public function testCreateWithAllPossibleData()
    {

        $data = [
            'email' => 'fulano@dominio.com',
            'password' => 'dhsahdksada'
        ];

        $recrutador = $this->recrutadorService->create($data);

        $this->assertInstanceOf(Recrutador::class, $recrutador);
        $this->assertInstanceOf(\DateTime::class, $recrutador->getCreated());
        $this->assertNull($recrutador->getUpdated());
        $this->assertEntityData($recrutador, $data, ['password']);
        $this->assertTrue((new Bcrypt())->verify($data['password'], $recrutador->getPassword()));
    }

    public function testCreateOnlyWithRequiredData()
    {
        $data = [
            'email' => 'beltrano@dominio.com',
            'password' => 'dhsahdw32dksada'
        ];

        $recrutador = $this->recrutadorService->create($data);

        $this->assertInstanceOf(Recrutador::class, $recrutador);
        $this->assertInstanceOf(\DateTime::class, $recrutador->getCreated());
        $this->assertNull($recrutador->getUpdated());
        $this->assertEntityData($recrutador, $data, ['password']);
        $this->assertTrue((new Bcrypt())->verify($data['password'], $recrutador->getPassword()));
    }

    public function testUpdateWithAllPossibleData()
    {
        $recrutador = new Recrutador(1);
        $recrutador->set_Password('dsadsajdas');

        $data = [
            'email' => 'fulano@dominio.com',
            'password' => 'dhsahdksada'
        ];

        $recrutador = $this->recrutadorService->update($recrutador, $data);

        $this->assertInstanceOf(Recrutador::class, $recrutador);
        $this->assertInstanceOf(\DateTime::class, $recrutador->getUpdated());
        $this->assertEntityData($recrutador, $data, ['password']);
    }

    public function testUpdateonlyWithRequiredData()
    {
        $recrutador = new Recrutador(1);
        $recrutador->set_Password('dsadsajdas');

        $data = [
            'email' => 'fulano@dominio.com'
        ];

        $recrutador = $this->recrutadorService->update($recrutador, $data);

        $this->assertInstanceOf(Recrutador::class, $recrutador);
        $this->assertInstanceOf(\DateTime::class, $recrutador->getUpdated());
        $this->assertEntityData($recrutador, $data, ['password']);
    }

}