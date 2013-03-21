<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130321202621 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE classi_corso DROP CONSTRAINT classi_corsi_pkey');
        $this->addSql('ALTER TABLE classi_corso ADD CONSTRAINT classi_corso_cod_corso_key UNIQUE (cod_corso)');
        $this->addSql('CREATE SEQUENCE classi_corso_id_seq');
        $this->addSql("ALTER TABLE classi_corso ADD id INTEGER NOT NULL DEFAULT nextval('classi_corso_id_seq') PRIMARY KEY");
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE classi_corso DROP CONSTRAINT classi_corso_pkey');
        $this->addSql('ALTER TABLE classi_corso DROP id');
        $this->addSql('DROP SEQUENCE classi_corso_id_seq');
        $this->addSql('ALTER TABLE classi_corso DROP CONSTRAINT classi_corso_cod_corso_key');
        $this->addSql('ALTER TABLE classi_corso ADD CONSTRAINT classi_corsi_pkey PRIMARY KEY (cod_corso)');
    }
}
