<?php

namespace App\Validation;

use App\Entity\Candidato;
use App\Entity\Habilidade;
use Core\Validator\DoctrineValidator\UniqueObject;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Filter\StringToLower;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToInt;
use Laminas\I18n\Validator\IsInt;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\Explode;
use Laminas\Validator\GreaterThan;
use Laminas\Validator\IsInstanceOf;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\Regex;
use Laminas\Validator\StringLength;

/**
 * Description of CandidatoValidation
 *
 * @author adair
 */
class CandidatoValidation extends InputFilter
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        //id
        $this->add([
            'name'      => 'id',
            'required'  => false
        ]);

        //email
        $this->add([

            'name'      => 'email',
            'required'  =>  true,
            'filters'   => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
                ['name' => StringToLower::class]

            ],
            'validators' => [
                [
                    'name'      => NotEmpty::class,
                    'options'   => [
                        'messages'  => [
                            NotEmpty::IS_EMPTY => 'Email não informado, favor informá-lo',
                        ]
                    ],
                ],
                [
                    'name'      => StringLength::class,
                    'options'   => [
                        'max'   => 100,
                        'messages' => [
                            StringLength::INVALID   => 'Email não informado, favor informá-lo',
                            StringLength::TOO_LONG  => 'O email pode ter no máximo 100 caracteres',
                        ]
                    ]
                ],
                [
                    'name'      => EmailAddress::class, 
                    'options'   => [
                        'message' => [
                            EmailAddress::INVALID => 'Endereço de email inválido'
                        ]
                    ]
                ],
                [
                    'name'      => UniqueObject::class,
                    'options'   => [
                        'object_manager'    => $this->entityManager,
                        'object_repository' => $this->entityManager->getRepository(Candidato::class),
                        'use_context'       => true,
                        'fields'            => ['email'],
                        'identifier'        => ['id'],
                        'message'           => [
                            UniqueObject::ERROR_OBJECT_NOT_UNIQUE => 'Já existe um candidato com este email'
                        ]
                    ]
                ]
                
            ],
        ]);

        //nome
        $this->add([

            'name'      => 'nome',
            'required'  =>  true,
            'filters'   => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
                ['name' => StringToLower::class]

            ],
            'validators' => [
                [
                    'name'      => NotEmpty::class,
                    'options'   => [
                        'messages'  => [
                            NotEmpty::IS_EMPTY => 'Nome não informado, favor informá-lo',
                        ]
                    ],
                ],
                [
                    'name'      => StringLength::class,
                    'options'   => [
                        'max'   => 100,
                        'messages' => [
                            StringLength::INVALID   => 'Nome não informado, favor informá-lo',
                            StringLength::TOO_LONG  => 'O nome pode ter no máximo 100 caracteres',
                        ]
                    ]
                ]                
            ],
        ]);

        //linkedin
        $this->add([

            'name'      => 'linkedin',
            'required'  =>  false,
            'filters'   => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
                ['name' => StringToLower::class]

            ],
            'validators' => [
                [
                    'name'      => NotEmpty::class,
                    'options'   => [
                        'messages'  => [
                            NotEmpty::IS_EMPTY => 'Linkedin não informado, favor informá-lo',
                        ]
                    ],
                ],
                [
                    'name'      => StringLength::class,
                    'options'   => [
                        'max'   => 300,
                        'messages' => [
                            StringLength::INVALID   => 'Linkedin não informado, favor informá-lo',
                            StringLength::TOO_LONG  => 'O linkedin pode ter no máximo 300 caracteres',
                        ]
                    ]
                ],
                [
                    'name'      => UniqueObject::class,
                    'options'   => [
                        'object_manager'    => $this->entityManager,
                        'object_repository' => $this->entityManager->getRepository(Candidato::class),
                        'use_context'       => true,
                        'fields'            => ['linkedin'],
                        'identifier'        => ['id'],
                        'message'           => [
                            UniqueObject::ERROR_OBJECT_NOT_UNIQUE => 'Já existe um candidato com este linkedin'
                        ]
                    ]
                ]
                
            ],
        ]);

        //habilidades
        $this->add([

            'name'      => 'habilidades',
            'required'  => true,
            'validators' => [
                [
                    'name' => NotEmpty::class,
                    'options'   => [
                        'messages' => [
                            NotEmpty::IS_EMPTY => 'habilidades não informadas, favor informá-la'
                        ]
                    ]
                ],
                [
                    'name' => Explode::class,
                    'options' => [
                        'validator' => [
                            "name"      => IsInstanceOf::class,
                            "options"   =>[
                                "className" => Habilidade::class,
                                "messages" => [
                                    IsInstanceOf::NOT_INSTANCE_OF => "Habilidade inv[alida, para criar/editar uma candidadto, é necessário informar habilidades válidas"
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]);

        /*idade*/
        $this->add([

            'name'      => 'idade',
            'required'  => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
                ['name' => ToInt::class]
            ],
            'validators' => [
                [
                    'name' => NotEmpty::class,
                    'options'   => [
                        'messages' => [
                            NotEmpty::IS_EMPTY => 'Idade não informada, favor informá-la'
                        ]
                    ]
                ],
                [
                    'name' => IsInt::class,
                    'options'   => [
                        'messages' => [
                            IsInt::INVALID  => "Idade do candidato, inválida",
                            IsInt::NOT_INT  => "Idade do candidato, inválida"
                        ]
                    ]
                ],
                [
                    'name' => GreaterThan::class,
                    'options'   => [
                        'min' => 0, //deve ser maior que zero
                        'messages' => [
                            GreaterThan::NOT_GREATER => "idade do candidato, inválida, a mesma deve ser maior que zero"
                        ]
                    ]
                ]
            ]
        ]);
    }

    public function setData($data)
    {
        //como esses campos fazem parte do DoctrineValidator
        //ele nao podem ser null
        $data['id'] = $data['id'] ?? '';
        $data['email'] = $data['email'] ?? '';
        $data['linkedin'] = $data['linkedin'] ?? '';
        

        $habilidades = [];

        if(isset($data['habilidades']) && is_array($data['habilidades'])){

            foreach($data['habilidades'] as $habilidade){

                if(
                    is_array($habilidade) &&
                    isset($habilidade['id'])
                ){
                    $habilidade = $this->entityManager->find(Habilidade::class, $habilidade['id']);
                }

                if($habilidade instanceof Habilidade){
                    $habilidades[] = $habilidade;
                }
            }
        }

        $data['habilidades'] = $habilidades;

        parent::setData($data);
    }
}