<?php

namespace App\Entity;

use Core\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Habilidade
 *
 * @ORM\Entity
 * @ORM\Table(name="habilidade")
 *
 * @author adair
 */
class Habilidade extends Entity
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
    private $name;
    
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
    public function getName(): string 
    {
        return $this->name;
    }


    /**
     * @param int $id
     * @return void
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * 
     * @param string $name
     * @return void
     */
    public function setName(string $name): void 
    {
        $this->name = $name;
    }
    
}