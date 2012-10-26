<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20120925142409 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('DELETE FROM fos_user');
        $this->addSql("SELECT setval('fos_user_id_seq', (SELECT MAX(id_utente) FROM utente))");
        
        $roles = array (
                'ROLE_STUDENT' => 2,
                'ROLE_COLLABORATOR' => 4,
                'ROLE_TUTOR' => 8,
                'ROLE_PROFESSOR' => 16,
                'ROLE_STAFF' => 32,
                'ROLE_ADMIN' => 64
        );
        
        foreach($roles as $role => $groups) {
            $this->addSql('UPDATE fos_user SET roles = ? WHERE groups = ?', array(serialize(array($role)), $groups));
        }
    }

    public function down(Schema $schema)
    {
        $this->addSql('DELETE FROM fos_user');
        $this->addSql("SELECT setval('fos_user_id_seq', 1)");
    }
}
