<?php

namespace App\Entity;

use Core\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description of Recrutador
 *
 * @ORM\Entity
 * @ORM\Table(name="recrutador")
 *
 * @author adair
 */
class Recrutador extends Entity
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
     * @ORM\Column(type="string", length=300)
     * 
     * @var string
     */
    private $password;
 
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
    public function getPassword(): string
    {
        return $this->password;
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
     * @param string $password
     * @return void
     */
    public function set_Password(string $password): void
    {
        $this->password = $password;
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
    
}