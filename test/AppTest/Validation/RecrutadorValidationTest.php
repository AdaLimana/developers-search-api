<?php

namespace AppTest\Validation;

use App\Entity\Recrutador;
use App\Validation\RecrutadorValidation;
use Core\PHPUnit\TestCaseValidation;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\Callback;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;

class RecrutadorValidationTest extends TestCaseValidation
{


    public function invalidDataProvider()
    {
        return [

            [/**Dados obrigatorios nao informados */
                [
                    /**email*/ /**Dado nao informado*/
                    /**password */ /**Dados nao informado */
                ],
                [
                    'email' => [NotEmpty::IS_EMPTY],
                    'password' => [NotEmpty::IS_EMPTY]
                ]
            ],
            [/**Dados extrapolando o maximo de caracteres permitidos */
                [
                    'email' => $this->generateRandomString('101'),
                    'password' => $this->generateRandomString('61', true)
                ],
                [
                    'email' => [StringLength::TOO_LONG],
                    'password' => [StringLength::TOO_LONG]
                ] 
            ],
            [/**Dados nao atingindo o minimo de caracteres necessario */
                [
                    'email' => 'fulano@dominio.com',
                    'password' => $this->generateRandomString('7', true) /**< 8 caracteres */
                ],
                [
                    'password' => [StringLength::TOO_SHORT]
                ] 
            ],
            [/**Dados no formato invhalidp*/
                [
                    'email' => 'jkdsksa',
                    'password' => '3425783282' /**< 8 caracteres */
                ],
                [
                    'email' => [EmailAddress::INVALID_FORMAT]
                ] 
            ]
        ];
    }

    public function validDataProvider()
    {
        return [

            /**0 */[ /**somente os dados obrigatorios */
                [
                    'email' => 'beltrano@dominio.com',
                    'password' => 'dsadhfasa'
                ]    
            ],
            /**1 */[ /**quando tem id, o password nao eh obrigatorio */
                [
                    'id' => 1,
                    'email' => 'siclano@dominio.com'
                ]
            ],
            /**2 */[/**dados no limite maximo */
                [
                    'email' => 'asasa@dominio.com',
                    'password' => $this->generateRandomString(60, true) /**60 caracteres */
                ]
            ],
            /**2 */[/**dados no limite minimo */
                [
                    'email' => 'joao@dominio.com',
                    'password' => $this->generateRandomString(8, true) /**8 caracteres */
                ]
            ],
            
            
            [
                [
                    'email' => 'nome@dominio.com',
                    'password' => $this->generateRandomString(8, true) /**8 caracteres */
                ]
            ]
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

    public function testChangePasswordInformingInvalidOldpassword()
    {

        $recrutador = new Recrutador(1);
        $bcrypt = new Bcrypt();
        $recrutador->set_Password($bcrypt->create('12345678'));

        $recrutadorRepository = $this->createMock(ObjectRepository::class);
        $recrutadorRepository->expects($this->any())
            ->method('find')
            ->willReturn($recrutador);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->any())
                      ->method('getRepository')
                      ->willReturn($recrutadorRepository);

        $entityManager->expects($this->any())
                      ->method('find')
                      ->with(Recrutador::class, 1)
                      ->willReturn($recrutador);

        $validator = new RecrutadorValidation($entityManager);

        $data = [
            'id' => 1,
            'email' => 'joao@dominio.com',
            'newPassword' => 'novasenha',
            'oldPassword' => '12345679'
        ];

        $validator->setData($data);

        $this->assertFalse($validator->isValid());
        $this->assertArrayHasKey('oldPassword', $validator->getMessages());
        $this->assertArrayHasKey(Callback::INVALID_VALUE, $validator->getMessages()['oldPassword']);
    }

    public function testChangePasswordInformingValidOldpassword()
    {

        $recrutador = new Recrutador(1);
        $bcrypt = new Bcrypt();
        $recrutador->set_Password($bcrypt->create('12345678'));

        $recrutadorRepository = $this->createMock(ObjectRepository::class);
        $recrutadorRepository->expects($this->any())
            ->method('find')
            ->willReturn($recrutador);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->any())
                      ->method('getRepository')
                      ->willReturn($recrutadorRepository);

        $entityManager->expects($this->any())
                      ->method('find')
                      ->with(Recrutador::class, 1)
                      ->willReturn($recrutador);

        $validator = new RecrutadorValidation($entityManager);

        $data = [
            'id' => 1,
            'email' => 'joao@dominio.com',
            'newPassword' => 'novasenha',
            'oldPassword' => '12345678'
        ];

        $validator->setData($data);

        $this->assertTrue($validator->isValid());
    }

    protected function getValidation(): InputFilter
    {

        $recrutador = new Recrutador();

        $recrutadorRepository = $this->createMock(ObjectRepository::class);
        $recrutadorRepository->expects($this->any())
            ->method('find')
            ->willReturn($recrutador);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects($this->any())
                      ->method('getRepository')
                      ->willReturn($recrutadorRepository);

        $entityManager->expects($this->any())
                      ->method('find')
                      ->with(Recrutador::class, 1)
                      ->willReturn($recrutador);

        return new RecrutadorValidation($entityManager);
    }
}