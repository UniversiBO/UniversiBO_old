<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130408171315 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");
        
        $this->addSql("CREATE SEQUENCE comments_id_seq INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql("CREATE TABLE threads (id VARCHAR(255) NOT NULL, permalink VARCHAR(255) NOT NULL, is_commentable BOOLEAN NOT NULL, num_comments INT NOT NULL, last_comment_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))");
        $this->addSql("CREATE TABLE comments (id INT NOT NULL, thread_id VARCHAR(255) DEFAULT NULL, body TEXT NOT NULL, ancestors VARCHAR(1024) NOT NULL, depth INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, state INT NOT NULL, PRIMARY KEY(id))");
        $this->addSql("CREATE INDEX IDX_5F9E962AE2904019 ON comments (thread_id)");
        $this->addSql("ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AE2904019 FOREIGN KEY (thread_id) REFERENCES threads (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");
        
        $this->addSql("ALTER TABLE comments DROP CONSTRAINT FK_5F9E962AE2904019");
        $this->addSql("DROP SEQUENCE comments_id_seq");
        $this->addSql("DROP TABLE threads");
        $this->addSql("DROP TABLE comments");
    }
}
