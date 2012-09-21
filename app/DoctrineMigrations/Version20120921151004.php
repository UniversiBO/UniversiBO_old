<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20120921151004 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->dropViews();
        $this->addSql('ALTER TABLE canale ALTER COLUMN "nome_canale" TYPE character varying(200)');
        $this->createViews();
    }

    public function down(Schema $schema)
    {
        $this->dropViews();
        $this->addSql('ALTER TABLE canale ALTER COLUMN "nome_canale" TYPE character varying(100)');
        $this->createViews();
    }
    
    private function createViews()
    {
        $this->addSql('CREATE VIEW canale_noforum AS (SELECT * FROM canale WHERE canale.forum_attivo = \'N\')');
    }
    
    private function dropViews()
    {
        $this->addSql('DROP VIEW canale_noforum');
    }
}
