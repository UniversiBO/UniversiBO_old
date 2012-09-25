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
        $sql = '';
        $sql .= "INSERT INTO fos_user";
        $sql .= "(id, username, username_canonical, shib_username, email, email_canonical, enabled, salt, password, locked, expired, roles, credentials_expired, notifications, groups, last_login, phone)";
        $sql .= "(SELECT id_utente, username, LOWER(username), ad_username, email, LOWER(email), true, '', '', ban = 'S', false, '', false, notifica, groups, to_timestamp(ultimo_login), phone FROM utente WHERE sospeso <> 'S' ORDER BY id_utente);";
        
        $this->addSql($sql);
        
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
