<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130321233752 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");
        
        
        $this->addSql("ALTER TABLE channels ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL");
        $this->addSql("ALTER TABLE channels ADD groups INT NOT NULL");
        $this->addSql("ALTER TABLE channels ADD forum_id INT");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");

        $this->addSql("ALTER TABLE channels DROP updated_at");
        $this->addSql("ALTER TABLE channels DROP groups");
        $this->addSql("ALTER TABLE channels DROP forum_id");
    }
}
