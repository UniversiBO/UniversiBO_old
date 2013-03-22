<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20130322002508 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");
                
        $this->addSql("ALTER TABLE channels ALTER updated_at DROP NOT NULL");
        $this->addSql("ALTER TABLE channels ADD forum_group_id INT DEFAULT NULL");
        
        $sql = "INSERT INTO channels (id, type, name, slug, hits, updated_at, groups, forum_id) SELECT id_canale, tipo_canale, nome_canale, '', visite, to_timestamp(ultima_modifica), permessi_groups, id_forum FROM canale";
        $this->addSql($sql);
        
        $this->addSql("DELETE FROM channel_channelservice");
        $this->addSql("DELETE FROM channel_services");
        $this->addSql('DROP SEQUENCE channel_services_id_seq');
        $this->addSql('CREATE SEQUENCE channel_services_id_seq');
        
        $this->addSql(<<<EOT
INSERT 
    INTO channel_services 
        (id, name) 
    VALUES
        (nextval('channel_services_id_seq'), 'files'),
        (nextval('channel_services_id_seq'), 'forum'),
        (nextval('channel_services_id_seq'), 'links'),
        (nextval('channel_services_id_seq'), 'news'),
        (nextval('channel_services_id_seq'), 'student_files')
EOT
        );

        $i = 0;
        $ids = [
            ++$i => 'files_attivo',
            ++$i => 'forum_attivo',
            ++$i => 'links_attivo',
            ++$i => 'news_attivo',
            ++$i => 'files_studenti_attivo',
        ];
        
        foreach($ids as $id => $service) {
        
            $this->addSql(<<<EOT
INSERT 
    INTO channel_channelservice
        (channel_id, channelservice_id) 
    SELECT
        id_canale, $id
    FROM canale
    WHERE $service = 'S'
EOT
            );        
        }
        
        $this->addSql('ALTER TABLE classi_corso DROP CONSTRAINT classi_corso_id_canale_fkey');
        $this->addSql('ALTER TABLE classi_corso ADD CONSTRAINT classi_corso_id_canale_fkey FOREIGN KEY(id_canale) REFERENCES channels(id)');
        $this->addSql('ALTER TABLE facolta DROP CONSTRAINT facolta_id_canale_fkey');
        $this->addSql('ALTER TABLE facolta ADD CONSTRAINT facolta_id_canale_fkey FOREIGN KEY(id_canale) REFERENCES channels(id)');
        
        $this->addSql('DROP VIEW stat_canale_file');
        $this->addSql('DROP VIEW stat_canale_news');
        $this->addSql('DROP VIEW canale_noforum');
        $this->addSql("DROP SEQUENCE canale_id_canale_seq CASCADE");
        $this->addSql('DROP TABLE canale');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "postgresql", "Migration can only be executed safely on 'postgresql'.");
        
        $this->addSql("CREATE TABLE canale (id_canale SERIAL NOT NULL, tipo_canale INT NOT NULL, nome_canale VARCHAR(200) DEFAULT NULL, immagine VARCHAR(50) DEFAULT NULL, visite INT NOT NULL, ultima_modifica INT DEFAULT NULL, permessi_groups INT DEFAULT NULL, files_attivo CHAR(255) DEFAULT NULL, news_attivo CHAR(255) DEFAULT NULL, forum_attivo CHAR(255) DEFAULT NULL, id_forum INT DEFAULT NULL, group_id INT DEFAULT NULL, links_attivo CHAR(255) DEFAULT NULL, files_studenti_attivo CHAR(255) DEFAULT NULL, PRIMARY KEY(id_canale))");
        $this->addSql("CREATE VIEW canale_noforum AS (SELECT * FROM canale WHERE forum_attivo = 'N')");
        $this->addSql("CREATE VIEW stat_canale_file AS (SELECT c.id_canale, count(fic.id_file) AS canale_files, sum(fi.dimensione) AS canale_dimensione, sum(fi.download) AS canale_download
   FROM file_canale fic, canale c, file fi
  WHERE fic.id_canale = c.id_canale AND fi.id_file = fic.id_file
  GROUP BY c.id_canale)");
        
        $this->addSql("CREATE VIEW stat_canale_news AS (SELECT c.id_canale, count(nc.id_news) AS canale_news
   FROM canale c, news_canale nc
  WHERE c.id_canale = nc.id_canale
  GROUP BY c.id_canale
  ORDER BY count(nc.id_news) DESC)");
        
        $this->addSql("INSERT INTO canale (id_canale, tipo_canale, nome_canale, immagine, ultima_modifica, visite, permessi_groups, id_forum) SELECT id, type::int, name, '', EXTRACT(EPOCH FROM updated_at), hits, groups, forum_id FROM channels");
        $this->addSql("UPDATE canale SET news_attivo = 'N', files_attivo = 'N', forum_attivo = 'N', files_studenti_attivo='N'");
                
        $this->addSql('ALTER TABLE classi_corso DROP CONSTRAINT classi_corso_id_canale_fkey');
        $this->addSql('ALTER TABLE classi_corso ADD CONSTRAINT classi_corso_id_canale_fkey FOREIGN KEY(id_canale) REFERENCES canale(id_canale)');
        $this->addSql('ALTER TABLE facolta DROP CONSTRAINT facolta_id_canale_fkey');
        $this->addSql('ALTER TABLE facolta ADD CONSTRAINT facolta_id_canale_fkey FOREIGN KEY(id_canale) REFERENCES canale(id_canale)');
        
        $this->addSql("DELETE FROM channels");
        $this->addSql("DELETE FROM channel_channelservice");
        $this->addSql("DELETE FROM channel_services");
        $this->addSql('DROP SEQUENCE channel_services_id_seq');
        $this->addSql('CREATE SEQUENCE channel_services_id_seq');
        $this->addSql("ALTER TABLE channels ALTER updated_at SET NOT NULL");
        $this->addSql("ALTER TABLE channels DROP forum_group_id");
    }
}
