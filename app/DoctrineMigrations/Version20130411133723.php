<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130411133723 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");
        
        $this->addSql("CREATE SEQUENCE beta_requests_id_seq INCREMENT BY 1 MINVALUE 1 START 1");
        $this->addSql("CREATE TABLE beta_requests (id INT NOT NULL, request_user_id INT NOT NULL, approval_user_id INT DEFAULT NULL, requested_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, approved_at TIMESTAMP(0) WITHOUT TIME ZONE, PRIMARY KEY(id))");
        $this->addSql("CREATE INDEX IDX_12B8FB2F8D4AA1C2 ON beta_requests (request_user_id)");
        $this->addSql("CREATE INDEX IDX_12B8FB2F332E6CD3 ON beta_requests (approval_user_id)");
        $this->addSql("ALTER TABLE beta_requests ADD CONSTRAINT FK_12B8FB2F8D4AA1C2 FOREIGN KEY (request_user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
        $this->addSql("ALTER TABLE beta_requests ADD CONSTRAINT FK_12B8FB2F332E6CD3 FOREIGN KEY (approval_user_id) REFERENCES fos_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");

        $this->addSql("DROP TABLE beta_requests");
        $this->addSql("DROP SEQUENCE beta_requests_id_seq");
    }
}
