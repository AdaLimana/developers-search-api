<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210320230912 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'CREATE TABLES candidato, habilidade and candidatos_habilidades';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE candidato_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE habilidade_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE candidato (id INT NOT NULL, email VARCHAR(100) NOT NULL, nome VARCHAR(100) NOT NULL, linkedin VARCHAR(300) NOT NULL, idade INT NOT NULL, created TIMESTAMP(0) WITH TIME ZONE NOT NULL, updated TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2867675AE7927C74 ON candidato (email)');
        $this->addSql('CREATE TABLE candidatos_habilidades (candidato_id INT NOT NULL, habilidade_id INT NOT NULL, PRIMARY KEY(candidato_id, habilidade_id))');
        $this->addSql('CREATE INDEX IDX_7741680BFE0067E5 ON candidatos_habilidades (candidato_id)');
        $this->addSql('CREATE INDEX IDX_7741680BFDD1D822 ON candidatos_habilidades (habilidade_id)');
        $this->addSql('CREATE TABLE habilidade (id INT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CC4E37CF5E237E06 ON habilidade (name)');
        $this->addSql('ALTER TABLE candidatos_habilidades ADD CONSTRAINT FK_7741680BFE0067E5 FOREIGN KEY (candidato_id) REFERENCES candidato (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE candidatos_habilidades ADD CONSTRAINT FK_7741680BFDD1D822 FOREIGN KEY (habilidade_id) REFERENCES habilidade (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE candidatos_habilidades DROP CONSTRAINT FK_7741680BFE0067E5');
        $this->addSql('ALTER TABLE candidatos_habilidades DROP CONSTRAINT FK_7741680BFDD1D822');
        $this->addSql('DROP SEQUENCE candidato_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE habilidade_id_seq CASCADE');
        $this->addSql('DROP TABLE candidato');
        $this->addSql('DROP TABLE candidatos_habilidades');
        $this->addSql('DROP TABLE habilidade');
    }
}
