<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20120924000212 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");
        
        $this->addSql("CREATE TABLE fos_user (id SERIAL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, shib_username VARCHAR(255), enabled BOOLEAN NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, locked BOOLEAN NOT NULL, expired BOOLEAN NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, roles TEXT NOT NULL, credentials_expired BOOLEAN NOT NULL, credentials_expire_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, phone VARCHAR(15), notifications INT NOT NULL, PRIMARY KEY(id))");
        $this->addSql("CREATE UNIQUE INDEX UNIQ_957A647992FC23A8 ON fos_user (username_canonical)");
        $this->addSql("CREATE UNIQUE INDEX UNIQ_957A6479A0D96FBF ON fos_user (email_canonical)");
        $this->addSql("COMMENT ON COLUMN fos_user.roles IS '(DC2Type:array)'");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql");
        
        $this->addSql("DROP TABLE fos_user");
    }
}
