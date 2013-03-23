<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130323165858 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");
        
        $this->addSql("CREATE TABLE channels_schools (id INT NOT NULL, school_id INT NOT NULL, PRIMARY KEY(id))");
        $this->addSql("CREATE UNIQUE INDEX UNIQ_2079D8B6C32A47EE ON channels_schools (school_id)");
        $this->addSql("ALTER TABLE channels_schools ADD CONSTRAINT FK_2079D8B6C32A47EE FOREIGN KEY (school_id) REFERENCES schools (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE channels_schools ADD CONSTRAINT FK_2079D8B6BF396750 FOREIGN KEY (id) REFERENCES channels (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("CREATE UNIQUE INDEX UNIQ_48292C05E237E06 ON channel_services (name)");
        $this->addSql("ALTER TABLE channels ADD discr VARCHAR(255) NOT NULL DEFAULT 'default'");
        $this->addSql("ALTER TABLE channels ALTER discr DROP DEFAULT");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");
        
        $this->addSql('DROP INDEX UNIQ_48292C05E237E06');
        $this->addSql("DROP TABLE channels_schools");
        $this->addSql("ALTER TABLE channels DROP discr");
    }
}
