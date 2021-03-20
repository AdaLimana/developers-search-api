<?php

namespace AppTest\Validation;

use App\Entity\Candidato;
use App\Entity\Habilidade;
use App\Entity\Recrutador;
use App\Validation\CandidatoValidation;
use Core\PHPUnit\TestCaseValidation;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\GreaterThan;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;

class CandidatoValidationTest extends TestCaseValidation
{

    public function invalidDataProvider()
    {
        return [

            [/**Dados obrigatorios nao informados */
                [
                    /**email*/ /**Dado nao informado*/
                    /**nome*/ /**Dados nao informado */
                    /**habilidades */ /**Dados nao informado */
                    /**idade */ /**Dados nao informado */
                ],
                [
                    'email' => [NotEmpty::IS_EMPTY],
                    'nome' => [NotEmpty::IS_EMPTY],
                    'habilidades' => [NotEmpty::IS_EMPTY],
                    'idade' => [NotEmpty::IS_EMPTY]
                ]
            ],
            [/**Dados extrapolando o maximo de caracteres permitidos */
                [
                    'email' => $this->generateRandomString('101'),
                    'nome' => $this->generateRandomString('101'),
                    'habilidades' => [new Habilidade()],
                    'idade' => 21,
                    'linkedin' => $this->generateRandomString('301')
                ],
                [
                    'email' => [StringLength::TOO_LONG],
                    'nome' => [StringLength::TOO_LONG],
                    'linkedin' => [StringLength::TOO_LONG]
                ] 
            ],
            [/**Dados nao atingindo o minimo de caracteres necessario ou valor exigido */
                [
                    'email' => 'fulano@dominio.com',
                    'nome' => 'eltrano',
                    'habilidade' => [],
                    'idade' => 0
                ],
                [
                    'habilidades' => [NotEmpty::IS_EMPTY],
                    'idade' => [GreaterThan::NOT_GREATER]
                ] 
            ],
            [/**Dados no formato invhalidp*/
                [
                    'email' => 'fula.dominio.com',
                    'nome' => 'eltrano',
                    'habilidades' => 'dasdasd',
                    'idade' => '12',
                    'linkedin' => 'dkosadas.com'
                ],
                [
                    'email' => [EmailAddress::INVALID_FORMAT],
                    'habilidades' => [NotEmpty::IS_EMPTY],
                    // 'linkedin' => [Uri::INVALID]
                ] 
            ]
        ];
    }

    public function validDataProvider()
    {
        return [

            [ /**somente os dados obrigatorios */
                [
                    'email' => 'fulano@dominio.com',
                    'nome' => 'maria',
                    'habilidades' => [new Habilidade()],
                    'idade' => 21,
                ],    
            ],
            [ /**todos os dados possiveis */
                [
                    'email' => 'fulano@dominio.com',
                    'nome' => 'maria',
                    'habilidades' => [new Habilidade()],
                    'idade' => 21,
                    'linkedin' => 'https://linkedin.com'
                ],    
            ],
            [/**dados no limite maximo */
                [
                    'email' => 'fulano@dominio.com',
                    'nome' => $this->generateRandomString(100),
                    'habilidades' => [new Habilidade()],
                    'idade' => 21,
                    'linkedin' => $this->generateRandomString(300)
                ],
            ],
            [/**dados no limite minimo */
                [
                    'email' => 'fulano@dominio.com',
                    'nome' => 'maria',
                    'habilidades' => [new Habilidade()],
                    'idade' => 1,
                ],
            ],
            

        ];
    }

    /**
     * @dataProvider invalidDataProvider
     * 
     * @param array $data
     * @param array $errors
     */
    public function testInformingInvalidData(array $data, array $errors)
    {
        $this->informingInvalidData($data, $errors);
    }

    /**
     * @dataProvider validDataProvider
     * 
     * @param array $data
     */
    public function testInformingValidData(array $data)
    {
        $this->informingValidData($data);
    }

    protected function getValidation(): InputFilter
    {

        $candidato = new Candidato();

        $candidatoRepository = $this->createMock(ObjectRepository::class);
        $candidatoRepository->expects($this->any())
            ->method('find')
            ->willReturn($candidato);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->any())
                      ->method('getRepository')
                      ->willReturn($candidatoRepository);

        $entityManager->expects($this->any())
                      ->method('find')
                      ->with(Recrutador::class, 1)
                      ->willReturn($candidato);

        return new CandidatoValidation($entityManager);
    }
}