<?php

namespace App\Service\Entity;

use App\Entity\Candidato;
use App\Validation\CandidatoValidation;
use Core\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Laminas\Crypt\Password\Bcrypt;

/**
 * Description of CandidatoService
 *
 * @author adair
 */
class CandidatoService
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
                                      ->select('candidato')
                                      ->addSelect('habilidades')
                                      ->from(Candidato::class, 'candidato')
                                      ->leftJoin('candidato.habilidades', 'habilidades');

            if(
                is_array($options) &&
                isset($options['filters']) &&
                is_array($options['filters']) &&
                isset($options['filters']['habilidades'])
            ){
                $habilidades = explode('|', $options['filters']['habilidades']);
                $habilidades = array_filter($habilidades);

                $qb->andWhere('habilidades.id IN (:habilidades)')
                   ->setParameter('habilidades', $habilidades);
            }

            $result = [];

            if(
                is_array($options) && 
                isset($options['pagination']) &&
                is_array($options['pagination']) &&
                isset($options['pagination']['page']) &&
                isset($options['pagination']['count'])
            ){
                
                $result = $this->paginatedData($qb, $options['pagination']['page'], $options['pagination']['count']);
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
                           ->select('candidato')
                           ->addSelect('habilidades')
                           ->from(Candidato::class, 'candidato')
                           ->leftJoin('candidato.habilidades', 'habilidades')
                           ->where('candidato.id = :id')
                           ->setParameter('id', $id)
                           ->getQuery()
                           ->getArrayResult();
        }
        catch(\Exception $ex){
            throw new \Exception('Aconteceu um erro interno, tente novamente mais tarde', 500);
        }

        if(empty($result)){
            throw new \Exception('Candidato não encontrado', 404);
        }

        \Core\Entity\FormatAttributes::formatDate($result[0]);

        return $result[0];
    }

    /**
     * 
     * @param array $data
     * @return Candidato
     * @throws ValidationException
     */
    public function create(array $data): Candidato
    {
        $validator = new CandidatoValidation($this->entityManager);
        $validator->setData($data);

        if(!$validator->isValid()){
            throw new ValidationException($validator->getMessages(), 400);
        }

        $validatedData = $validator->getValues();

        $candidato = new Candidato();

        if(isset($validatedData['habilidades']) && is_array($validatedData['habilidades'])){
            foreach($validatedData['habilidades'] as $habilidade){
                $candidato->addHabilidade($habilidade);
            }
        }

        $candidato->setData($validatedData);

        return $candidato;
    }

    /**
     * 
     * @param Candidato $candidato 
     * @param array $data
     * @return Candidato
     * @throws ValidationException
     */
    public function update(Candidato $candidato, array $data): Candidato
    {
        $data['id'] = $candidato->getId();

        $validator = new CandidatoValidation($this->entityManager);
        $validator->setData($data);

        if(!$validator->isValid()){
            throw new ValidationException($validator->getMessages(), 400);
        }

        $validatedData = $validator->getValues();

        $candidato->getHabilidades()->clear();
        if(isset($validatedData['habilidades']) && is_array($validatedData['habilidades'])){
            foreach($validatedData['habilidades'] as $habilidade){
                $candidato->addHabilidade($habilidade);
            }
        }

        $candidato->setData($validatedData);
        $candidato->setUpdated(new \DateTime('now'));

        return $candidato;
    }

    private function paginatedData(QueryBuilder $qb, $page, $count): array
    {

        $qb->setFirstResult($count * ($page - 1))->setMaxResults($count);


        $paginator = new Paginator($qb, true);

        $result = [];
        $result['data'] = [];
        foreach($paginator as $pg){

            $result['data'][] = [
                'id'    => $pg->getId(),
                'nome'  => $pg->getNome(),
                'email' => $pg->getEmail(),
                'linkedin' => $pg->getLinkedin(),
                'idade' => $pg->getIdade(),
                'habilidades' => array_map(
                    function($value){
                        return [
                            'id' => $value->getId(), 
                            'name' => $value->getName()
                        ];
                    }, 
                    $pg->getHabilidades()->toArray()
                )
            ];    
        }

        $result['totalRecords'] = count($paginator);

        return $result;
    }


}