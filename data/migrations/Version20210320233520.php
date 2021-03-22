<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210320233520 extends AbstractMigration
{

    private $data = [
        ['id'   =>  1, 'name' => 'C#'],
        ['id'   =>  2, 'name' => 'JavaScript'],
        ['id'   =>  3, 'name' => 'NodeJs'],
        ['id'   =>  4, 'name' => 'Angular'],
        ['id'   =>  5, 'name' => 'Ionic'],
        ['id'   =>  6, 'name' => 'Mensageria'],
        ['id'   =>  7, 'name' => 'PHP'],
        ['id'   =>  8, 'name' => 'Laravel'],
    ];

    public function getDescription() : string
    {
        return 'INSERT DATA INTO habilidade TABLE';
    }

    public function up(Schema $schema) : void
    {
        foreach ($this->data as $habilidade){
            $this->addSql("INSERT INTO habilidade(id, name) VALUES (:id, :name)", $habilidade);
        }
    }

    public function down(Schema $schema) : void
    {
        foreach ($this->data as $habilidade){
            $this->addSql("DELETE FROM habilidade WHERE id = :id", $habilidade);
        }            
    }
}