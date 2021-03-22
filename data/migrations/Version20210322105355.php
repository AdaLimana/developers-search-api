<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210322105355 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'INSERT INTO rcerutador TABLE the admin';
    }

    public function up(Schema $schema) : void
    {
        $admin = [
            'id'    => 1,
            'email' => 'admin@domain.com',
            'password' => '$2y$10$K8HqoFNlZW7K3mlUtv//HuToXS79KgcoXaAWkgmGm6DmmpO.oL0/q', //admin_pass
            'created' => (new \DateTime('now'))->format('c')
        ];

        $this->addSql('INSERT INTO recrutador(id, email, password, created) VALUES (:id, :email, :password, :created)', $admin);
    }

    public function down(Schema $schema) : void
    {
        $this->addSql("DELETE FROM recrutador WHERE id = 1");
    }
}
