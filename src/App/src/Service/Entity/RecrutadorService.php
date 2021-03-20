<?php

namespace App\Service\Entity;

use App\Entity\Recrutador;
use App\Validation\RecrutadorValidation;
use Core\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
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

    public function getList(array $options = null)
    {
        try{
            $result = $this->entityManager
                           ->createQueryBuilder()
                           ->select('recrutador')
                           ->from(Recrutador::class, 'recrutador')
                           ->getQuery()
                           ->getArrayResult();
        }
        catch(\Exception $ex){
            throw new \Exception($ex->getMessage(), 500);
        }

        if(empty($result)){
            throw new \Exception('Recrutador não encontrado', 404);
        }

        array_walk(
            $result, 
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
                           ->select('recrutador')
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