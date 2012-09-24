<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20120924165745 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE fos_user ADD groups INT NOT NULL DEFAULT 0');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE fos_user DROP groups');
    }
}
