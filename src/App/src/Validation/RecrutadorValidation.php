<?php

namespace App\Validation;

use App\Entity\Recrutador;
use Core\Validator\DoctrineValidator\UniqueObject;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Crypt\Password\Bcrypt;
use Laminas\Filter\StringToLower;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Laminas\Filter\ToNull;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\Callback;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\StringLength;

/**
 * Description of RecrutadorValidation
 *
 * @author adair
 */
class RecrutadorValidation extends InputFilter
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
                        'object_repository' => $this->entityManager->getRepository(Recrutador::class),
                        'use_context'       => true,
                        'fields'            => ['email'],
                        'identifier'        => ['id'],
                        'message'           => [
                            UniqueObject::ERROR_OBJECT_NOT_UNIQUE => 'Já existe um recrutador com este email'
                        ]
                    ]
                ]
                
            ],
        ]);
            
        //password
        $this->add([
            
            'name'      => 'password',
            'required'  =>  false,
            'filters'   => [
                ['name' => StripTags::class],        
                ['name' => StringTrim::class],//remove espaços do inicio e fim
                ["name" => ToNull::class, "options" => [ToNull::TYPE_STRING]]
            ],
            'validators' => [
                [
                    'name'      => NotEmpty::class,
                    'options'   => [
                        'messages'  => [
                            NotEmpty::IS_EMPTY => 'Senha não informada, favor informá-la',
                        ]
                    ],
                ],
                [
                    'name'      => StringLength::class,
                    'options'   => [
                        'max'   => 60,
                        'min'   => 8,
                        'messages' => [
                            StringLength::INVALID   => 'Senha não informada, favor informá-la',
                            StringLength::TOO_LONG  => 'A senha pode ter no máximo 60 caracteres',
                            StringLength::TOO_SHORT => 'A senha precisa ter no mínimo 8 caracteres',
                        ],
                    ],
                ],
            ],
            
        ]);

        //oldPassword
        $this->add([
            
            'name'      => 'oldPassword',
            'required'  => false,
            'filters'   => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],//remove espaços do inicio e fim
                ["name" => ToNull::class, "options" => [ToNull::TYPE_STRING]]
            ],
            'validators' => [
                [
                    'name'      => NotEmpty::class,
                    'options'   => [
                        'messages'  => [
                            NotEmpty::IS_EMPTY => 'Senha antiga não informada, favor informá-la',
                        ]
                    ],
                ],
            ]    
        ]);
        
        //newPassword
        $this->add([
            
            'name'      => 'newPassword',
            'required'  => false,
            'filters'   => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],//remove espaços do inicio e fim
                ["name" => ToNull::class, "options" => [ToNull::TYPE_STRING]]
            ],
            'validators' => [
                [
                    'name'      => NotEmpty::class,
                    'options'   => [
                        'messages'  => [
                            NotEmpty::IS_EMPTY => 'Senha nova não informada, favor informá-la',
                        ]
                    ],
                ],
                [
                    'name'      => StringLength::class,
                    'options'   => [
                        'max'   => 60,
                        'min'   => 8,
                        'messages' => [
                            StringLength::TOO_LONG  => 'A senha pode ter no máximo 60 caracteres',
                            StringLength::TOO_SHORT => 'A senha precisa ter no mínimo 8 caracteres',
                        ],
                    ],
                ],
            ]
        ]);


    }

    public function setData($data)
    {
        //como esses campos fazem parte do DoctrineValidator
        //ele nao podem ser null
        $data['id'] = $data['id'] ?? '';
        $data['email'] = $data['email'] ?? '';

        if(!isset($data['id']) || $data['id'] === ''){
            $this->get('password')->setRequired(true);
        }
        else if(!empty($data['oldPassword']) ||!empty($data['newPassword'])){
            $this->changePasswordValidator($data);
        }
        parent::setData($data);
    }

    private function changePasswordValidator(array $data)
    {
        $recrutador = $this->entityManager->find(Recrutador::class, $data['id']);

        if(!$recrutador instanceof Recrutador){
            throw new \Exception('recrutador não cadastrado', 404);
        }

        $this->get('oldPassword')->setRequired(true);
        $this->get('newPassword')->setRequired(true);

        if(isset($data['oldPassword'])){
            
            $this->get('oldPassword')
                 ->getValidatorChain()
                 ->attachByName(
                     Callback::class,
                     [
                         'callback' => function($value) use($recrutador){
                             $bcrypt = new Bcrypt();

                             return $bcrypt->verify($value, $recrutador->getPassword());
                         },
                         'messages' => [
                             Callback::INVALID_VALUE => 'Senha incorreta'
                         ]
                     ]
                 );
        }
    } 

}