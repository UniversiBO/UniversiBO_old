<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20121026172131 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->addSql('DELETE FROM fos_user');
        $this->addSql("SELECT setval('fos_user_id_seq', (SELECT MAX(id_utente) FROM utente))");
        
        $roles = array (
                'ROLE_STUDENT' => 2,
                'ROLE_MODERATOR' => 4,
                'ROLE_TUTOR' => 8,
                'ROLE_PROFESSOR' => 16,
                'ROLE_STAFF' => 32,
                'ROLE_ADMIN' => 64
        );
        
        $query =<<<EOT
INSERT 
    INTO fos_user
    (
        id,
        username,
        username_canonical,
        email,
        email_canonical,
        enabled,
        salt,
        password,
        last_login,
        locked,
        expired,
        roles,
        credentials_expired,
        phone,
        notifications,
        groups,
        username_locked
    )
    SELECT
        id_utente,
        username, 
        LOWER(username),
        ad_username,
        LOWER(ad_username),
        true,
        salt,
        password,
        to_timestamp(ultimo_login),
        false,
        false,
        'a:0:{}',
        false,
        phone,
        notifica,
        groups,
        true
    FROM
        utente
    ORDER BY
        id_utente
EOT;
        $this->addSql($query);
        
        $query =<<<EOT
UPDATE fos_user u
    SET
        locked = true
    WHERE
        id IN
        (
            SELECT
                id_utente
            FROM
                utente
            WHERE
                sospeso = 'S'
        )
EOT;
        $this->addSql($query);
        
        $query =<<<EOT
INSERT 
    INTO contacts
    (
        user_id,
        value,
        verified_at
    )
    SELECT
        id_utente,
        email,
        localtimestamp
    FROM
        utente
    WHERE
            sospeso = 'N'
EOT;
        $this->addSql($query);
        
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
