<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20120609002753 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf($this->platform->getName() !== 'postgresql');
        
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'/\\.pdf$/\' WHERE id_file_tipo = 2');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'/\\.doc$/\' WHERE id_file_tipo = 3');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'/\\.gif$/\' WHERE id_file_tipo = 4');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'/\\.htm(l)?$/\' WHERE id_file_tipo = 5');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'/\\.jp(e)?g$/\' WHERE id_file_tipo = 6');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'/\\.mp3$/\' WHERE id_file_tipo = 7');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'/\\.sxw$/\' WHERE id_file_tipo = 8');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'/\\.sxc$/\' WHERE id_file_tipo = 9');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'/\\.sxi$/\' WHERE id_file_tipo = 10');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'/\\.ppt$/\' WHERE id_file_tipo = 11');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'/\\.rtf$/\' WHERE id_file_tipo = 12');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'/\\.tex$/\' WHERE id_file_tipo = 13');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'/\\.txt$/\' WHERE id_file_tipo = 14');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'/\\.xls$/\' WHERE id_file_tipo = 15');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'/\\.bmp$/\' WHERE id_file_tipo = 16');
        
    }

    public function down(Schema $schema)
    {
        $this->abortIf($this->platform->getName() !== 'postgresql');
        
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'.pdf$\' WHERE id_file_tipo = 2');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'.doc$\' WHERE id_file_tipo = 3');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'.gif$\' WHERE id_file_tipo = 4');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'.(htm|html)$\' WHERE id_file_tipo = 5');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'.jpg$\' WHERE id_file_tipo = 6');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'.mp3$\' WHERE id_file_tipo = 7');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'.sxw$\' WHERE id_file_tipo = 8');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'.sxc$\' WHERE id_file_tipo = 9');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'.sxi$\' WHERE id_file_tipo = 10');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'.ppt$\' WHERE id_file_tipo = 11');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'.rtf$\' WHERE id_file_tipo = 12');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'.tex$\' WHERE id_file_tipo = 13');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'.txt$\' WHERE id_file_tipo = 14');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'.xls$\' WHERE id_file_tipo = 15');
        $this->addSql('UPDATE file_tipo SET pattern_riconoscimento = \'.bmp$\' WHERE id_file_tipo = 16');
    }
}
