<?php

namespace App\Entity;

use Core\Entity\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;

/**
 * Description of Candidato
 *
 * @ORM\Entity
 * @ORM\Table(name="candidato")
 *
 * @author adair
 */
class Candidato extends Entity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id", type="integer")
     * @var int
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=100)
     * @var string
     */
    private $nome;

    /**
     * @ORM\Column(type="string", length=300)
     * @var string
     */
    private $linkedin;

    /**
     * MUNITOS Candidatos TEM MUITAS Habilidades.
     * @ORM\ManyToMany(targetEntity="Habilidade")
     * @ORM\JoinTable(
     *      name="candidatos_habilidades",
     *      joinColumns={@ORM\JoinColumn(name="candidato_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="habilidade_id", referencedColumnName="id")}
     * )
     * @var Collection
     */
    private $habilidades;
    
    /**
     * @ORM\Column(name="idade", type="integer")
     * @var int
     */
    private $idade;

    /**
     *
     * @ORM\Column(type="datetimetz")
     *
     * @var \DateTime
     */
    private $created;
    
    /**
     *
     * @ORM\Column(type="datetimetz", nullable=true)
     *
     * @var \DateTime
     */
    private $updated;

    public function __construct($id = null) 
    {
        $this->setId($id);
        $this->setCreated(new \DateTime('now'));
        $this->habilidades = new ArrayCollection();
    }
    
    /**
     * 
     * @return int
     */
    public function getId():? int
    {
        return $this->id;
    }

    /**
     * 
     * @return string
     */
    public function getEmail(): string 
    {
        return $this->email;
    }

    /**
     * 
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * 
     * @return string
     */
    public function getLinkedin(): string
    {
        return $this->linkedin;
    }

    /**
     * @return Collection
     */
    public function getHabilidades(): Collection
    {
        return $this->habilidades;
    }

    /**
     * @return int
     */
    public function getIdade(): int
    {
        return $this->idade;
    }

    /**
     * 
     * @return \DateTime
     */
    public function getCreated(): \DateTime 
    {
        return $this->created;
    }

    /**
     * 
     * @return \DateTime
     */
    public function getUpdated(): ?\DateTime 
    {
        return $this->updated;
    }


    /**
     * @param int $id
     * @return void
     */
    private function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * 
     * @param string $email
     * @return void
     */
    public function setEmail(string $email): void 
    {
        $this->email = $email;
    }

    /**
     * 
     * @param string $nome
     * @return void
     */
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    /**
     * 
     * @param string $linkedin
     * @return void
     */
    public function setLinkedin(string $linkedin): void
    {
        $this->linkedin = $linkedin;
    }

    /**
     * 
     * @param int $indade
     * @return void
     */
    public function setIdade(int $idade): void
    {
        $this->idade = $idade;
    }

    /**
     * 
     * @param \DateTime $created
     * @return void
     */
    private function setCreated(\DateTime $created): void 
    {
        $this->created = $created;
    }

    /**
     * 
     * @param \DateTime $updated
     * @return void
     */
    public function setUpdated(\DateTime $updated): void 
    {
        $this->updated = $updated;
    }

    /**
     * 
     * @param Habilidade $habilidade
     */
    public function addHabilidade(Habilidade $habilidade) 
    {
        if(!$this->habilidades->contains($habilidade)){
            $this->habilidades->add($habilidade);
        }
    }

    /**
     * 
     * @param Habilidade $habilidade
     */
    public function removeHabilidade(Habilidade $habilidade)
    {
        $this->habilidades->removeElement($habilidade);
    }        
    
    /**
     * 
     * @param type $id
     * @return Habilidade|null
     */
    public function getHabilidadeById($id): ?Habilidade
    {        
        return $this->getElementById($id, $this->habilidades);
    }
    
    /**
     *
     * @param type $id
     * @param PersistentCollection $collection
     * @return Habilidade|null
     */
    private function getElementById($id, PersistentCollection $collection)
    {
        $expr = new Comparison("id", "=", $id);
        $criteria = new Criteria();
        $criteria->where($expr);
        $element = $collection->matching($criteria);
        if($element->count() == 1){
            return $element->first();
        }

        return null;
    }
}