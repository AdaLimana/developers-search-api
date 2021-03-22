<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210322161327 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'UPDATE recrutador_id_seq';
    }

    public function up(Schema $schema) : void
    {

        //no down nao reseta esse valor, pois, caso seja chamado o donw w o mesmo resetar o valor, e nesse
        //intervalo for adicionado um recrutador no sistema, o mesmo ira pegar o id 1, entao, caso seja rodada
        //novamente essa migration, iria dar conflito entre os ids, pois ja existe alguem co o id 1
        $this->addSql('SELECT setval(\'recrutador_id_seq\', (SELECT MAX(id) FROM recrutador))');

    }

    public function down(Schema $schema) : void
    {

    }
}
