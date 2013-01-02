<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

class Version20130102154033 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE classi_corso ADD CONSTRAINT classi_corso_id_canale_fkey FOREIGN KEY (id_canale) REFERENCES canale(id_canale) ON DELETE CASCADE ON UPDATE CASCADE');
        $this->addSql('ALTER TABLE facolta ADD CONSTRAINT facolta_id_canale_fkey FOREIGN KEY (id_canale) REFERENCES canale(id_canale) ON DELETE CASCADE ON UPDATE CASCADE');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE classi_corso DROP CONSTRAINT classi_corso_id_canale_fkey');
        $this->addSql('ALTER TABLE facolta DROP CONSTRAINT facolta_id_canale_fkey');
    }
}
