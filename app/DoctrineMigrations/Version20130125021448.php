<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130125021448 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE fos_user ADD forum_id INTEGER');
    }

    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE fos_user DROP forum_id');
    }
}
