<?php

namespace App\Service\Entity;

use App\Entity\Recrutador;
use App\Validation\RecrutadorValidation;
use Core\Exception\ValidationException;
use Core\Service\Entity\BaseEntityService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Laminas\Crypt\Password\Bcrypt;

/**
 * Description of RecrutadorService
 *
 * @author adair
 */
class RecrutadorService
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getList(array $options = null): array
    {
        try{

            $qb = $this->entityManager->createQueryBuilder()
                                      ->select('partial recrutador.{id, email, created, updated}')
                                      ->from(Recrutador::class, 'recrutador');
            $result = [];

            if(
                is_array($options) && 
                isset($options['pagination']) &&
                is_array($options['pagination']) &&
                isset($options['pagination']['page']) &&
                isset($options['pagination']['count'])
            ){
                $qb->setFirstResult($options['pagination']['count'] * ($options['pagination']['page'] - 1) )
                   ->setMaxResults($options['pagination']['count']);

                $paginator = new Paginator($qb, true);

                $result['data'] =  $paginator->getQuery()->getArrayResult();
                $result['totalRecords'] = count($paginator);

            }
            else{
                $result['data'] =  $qb->getQuery()->getArrayResult();
                $result['totalRecords'] = count($result['data']);
            }
        }
        catch(\Exception $ex){
            throw new \Exception($ex->getMessage(), 500);
        }

        array_walk(
            $result['data'], 
            function(&$value){ \Core\Entity\FormatAttributes::formatDate($value); }
        );

        return $result;
    }

    /**
     * 
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function get($id): array
    {

        try{
            $result = $this->entityManager
                           ->createQueryBuilder()
                           ->select('partial recrutador.{id, email, created, updated}')
                           ->from(Recrutador::class, 'recrutador')
                           ->where('recrutador.id = :id')
                           ->setParameter('id', $id)
                           ->getQuery()
                           ->getArrayResult();
        }
        catch(\Exception $ex){
            throw new \Exception('Aconteceu um erro interno, tente novamente mais tarde', 500);
        }

        if(empty($result)){
            throw new \Exception('Recrutador não encontrado', 404);
        }

        \Core\Entity\FormatAttributes::formatDate($result[0]);

        return $result[0];
    }

    /**
     * 
     * @param array $data
     * @return Recrutador
     * @throws ValidationException
     */
    public function create(array $data): Recrutador
    {
        $validator = new RecrutadorValidation($this->entityManager);
        $validator->setData($data);

        if(!$validator->isValid()){
            throw new ValidationException($validator->getMessages(), 400);
        }

        $validatedData = $validator->getValues();

        $recrutador = new Recrutador();
        $recrutador->setData($validatedData);

        $bcrypt = new Bcrypt();
        $recrutador->set_Password($bcrypt->create($validatedData['password']));

        return $recrutador;
    }

    /**
     * 
     * @param Recrutador $recrutador 
     * @param array $data
     * @return Recrutador
     * @throws ValidationException
     */
    public function update(Recrutador $recrutador, array $data): Recrutador
    {
        $data['id'] = $recrutador->getId();

        $validator = new RecrutadorValidation($this->entityManager);
        $validator->setData($data);

        if(!$validator->isValid()){
            throw new ValidationException($validator->getMessages(), 400);
        }

        $validatedData = $validator->getValues();

        if(isset($validatedData['newPassword'])){
            $bcrypt = new Bcrypt();
            $recrutador->set_Password($bcrypt->create($validatedData['newPassword']));
        }

        $recrutador->setData($validatedData);
        $recrutador->setUpdated(new \DateTime('now'));

        return $recrutador;
    }
}