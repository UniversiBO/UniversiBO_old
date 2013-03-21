<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130321223226 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");
        
        $this->addSql("CREATE SEQUENCE channels_id_seq INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql("CREATE SEQUENCE channel_services_id_seq INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql("CREATE TABLE channels (id INT NOT NULL, type VARCHAR(50) NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, hits INT NOT NULL, PRIMARY KEY(id))");
        $this->addSql("CREATE TABLE channel_channelservice (channel_id INT NOT NULL, channelservice_id INT NOT NULL, PRIMARY KEY(channel_id, channelservice_id))");
        $this->addSql("CREATE INDEX IDX_2A60AD5972F5A1AA ON channel_channelservice (channel_id)");
        $this->addSql("CREATE INDEX IDX_2A60AD59FBDA3C4 ON channel_channelservice (channelservice_id)");
        $this->addSql("CREATE TABLE channel_services (id INT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))");
        $this->addSql("ALTER TABLE channel_channelservice ADD CONSTRAINT FK_2A60AD5972F5A1AA FOREIGN KEY (channel_id) REFERENCES channels (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE channel_channelservice ADD CONSTRAINT FK_2A60AD59FBDA3C4 FOREIGN KEY (channelservice_id) REFERENCES channel_services (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");
        
        $this->addSql("ALTER TABLE channel_channelservice DROP CONSTRAINT FK_2A60AD5972F5A1AA");
        $this->addSql("ALTER TABLE channel_channelservice DROP CONSTRAINT FK_2A60AD59FBDA3C4");
        $this->addSql("DROP SEQUENCE channels_id_seq");
        $this->addSql("DROP SEQUENCE channel_services_id_seq");
        $this->addSql("DROP TABLE channels");
        $this->addSql("DROP TABLE channel_channelservice");
        $this->addSql("DROP TABLE channel_services");
    }
}
