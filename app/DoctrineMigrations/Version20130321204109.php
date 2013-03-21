<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130321204109 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $table = $schema->createTable('schools_degree_courses');
        
        $table->addColumn('school_id', 'integer');
        $table->addColumn('degree_course_id', 'integer');
        
        $table->setPrimaryKey(['school_id', 'degree_course_id']);
        
        $table->addForeignKeyConstraint('schools', ['school_id'], ['id']);
        $table->addForeignKeyConstraint('classi_corso', ['degree_course_id'], ['id']);
    }

    public function down(Schema $schema)
    {
        $schema->dropTable('schools_degree_courses');
    }
}
