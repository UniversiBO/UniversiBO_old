<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130323202611 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");
        
        $this->addSql("ALTER TABLE channels ALTER slug DROP NOT NULL");
        $this->addSql("ALTER TABLE channels ALTER slug TYPE VARCHAR(100)");
        $this->addSql("UPDATE channels SET slug = NULL");
        $this->addSql("CREATE UNIQUE INDEX UNIQ_F314E2B6989D9B62 ON channels (slug)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");
        
        $this->addSql("ALTER TABLE channels ALTER slug SET NOT NULL");
        $this->addSql("ALTER TABLE channels ALTER slug TYPE VARCHAR(255)");
        $this->addSql("DROP INDEX UNIQ_F314E2B6989D9B62");
    }
}
