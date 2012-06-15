<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20120615145022 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->dropViews($schema);
        
        $this->addSql('ALTER TABLE utente DROP default_style');
        
        $this->createViews($schema);
    }

    public function down(Schema $schema)
    {
        $this->dropViews($schema);
        
        $schema->getTable('utente')->addColumn('default_style', 'string', array(
                'nullable' => false,
                'length' => 10,
                'default' => 'unibo'
        ));

        $this->createViews($schema);
    }
    
    private function dropViews(Schema $schema)
    {
        $this->addSql('DROP VIEW IF EXISTS v_docenti CASCADE');
        $this->dropLoggatiView($schema, 24);
        $this->dropLoggatiView($schema, 168);
    }
    
    private function createViews(Schema $schema)
    {
        $this->addSql('CREATE VIEW v_docenti AS (SELECT u.* FROM utente u WHERE EXISTS (SELECT d.id_utente FROM docente d WHERE d.id_utente = u.id_utente))');
        $this->addSql('CREATE VIEW v_docenti_nounibo AS (SELECT d.* FROM v_docenti d WHERE d.email NOT LIKE \'%@unibo.it\')');
        $this->createLoggatiView($schema, 24);
        $this->createLoggatiView($schema, 168);
    }
    
    private function createLoggatiView(Schema $schema, $hours)
    {
        $seconds = $hours * 3600;
        
        $sql = <<<EOF
CREATE VIEW loggati_{$hours}h AS
(
    SELECT u.*
    FROM utente u
    WHERE
        u.ultimo_login > (SELECT MAX(u2.ultimo_login)
                          FROM user u2) - {$seconds}
)
EOF;
    }
    
    private function dropLoggatiView(Schema $schema, $hours)
    {
        $this->addSql('DROP VIEW IF EXISTS loggati_'.$hours.'h CASCADE');
    }
}
