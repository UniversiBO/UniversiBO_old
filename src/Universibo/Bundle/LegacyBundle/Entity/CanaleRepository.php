<?php

namespace Universibo\Bundle\LegacyBundle\Entity;

/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
use Doctrine\DBAL\Connection;

class CanaleRepository extends DoctrineRepository
{
    public function getTipoCanaleFromId($id)
    {
        $db = $this->getConnection();

        $query = 'SELECT tipo_canale FROM canale WHERE id_canale = ?';
        $stmt = $db->executeQuery($query, array($id));
        
        return $stmt->fetchColumn();
    }

    public function updateUltimaModifica(Canale $canale)
    {
        $db = $this->getConnection();
        $query = 'UPDATE canale SET ultima_modifica = ? WHERE id_canale = ?';
        
        return $db->executeUpdate($query, array($canale->getUltimaModifica(), $canale->getIdCanale())) > 0;
    }

    /**
     * @param int $idCanale
     * @return \Universibo\Bundle\LegacyBundle\Entity\Canale
     */
    public function find($idCanale)
    {
        $result = $this->findManyById(array($idCanale));

        return is_array($result) ? (isset($result[0]) ? $result[0] : null) : $result;
    }

    public function findManyById(array $idCanale)
    {
        $db = $this->getConnection();

        $query = 'SELECT tipo_canale, nome_canale, immagine, visite, ultima_modifica, permessi_groups, files_attivo, news_attivo, forum_attivo, id_forum, group_id, links_attivo, id_canale, files_studenti_attivo FROM canale WHERE id_canale IN (?) ORDER BY nome_canale;';
        $stmt = $db->executeQuery($query, array($idCanale), array(Connection::PARAM_INT_ARRAY));
        
        $elenco_canali = array();
        while (false !== ($row = $stmt->fetch())) {
            $elenco_canali[] = new Canale($row[12], $row[5], $row[4], $row[0], $row[2], $row[1], $row[3],
                    $row[7]=='S', $row[6]=='S', $row[8]=='S', $row[9], $row[10], $row[11]=='S',$row[13]=='S' );
        }

        return $elenco_canali;
    }

    public function findManyByType($type)
    {
        $db = $this->getConnection();

        $query = 'SELECT id_canale FROM canale WHERE tipo_canale = ?';
        $stmt = $db->executeQuery($query, array($type));

        $elenco_canali = array();
        while (false !== ($id = $stmt->fetchColumn())) {
            $elenco_canali[] = $id;
        }

        return count($elenco_canali) > 0 ? $elenco_canali : false;
    }

    public function addVisite(Canale $canale, $increment = 1)
    {
        $db = $this->getConnection();

        $query = 'UPDATE canale SET visite = visite + ? WHERE id_canale = ?';
        
        $canale->setVisite($canale->getVisite() + $increment);
        
        return $db->executeUpdate($query, array($increment, $canale->getIdCanale())) > 0; 
    }

    public function insert(Canale $canale)
    {
        $db = $this->getConnection();

        $files_attivo = ( $canale->getServizioFiles() ) ? 'S' : 'N';
        $news_attivo  = ( $canale->getServizioNews()  ) ? 'S' : 'N';
        $links_attivo = ( $canale->getServizioLinks() ) ? 'S' : 'N';
        $files_studenti_attivo = ( $canale->getServizioFilesStudenti() ) ? 'S' : 'N';
        if ( $canale->getServizioForum() ) {
            $forum_attivo = 'S';
            $forum_forum_id = $canale->getForumForumId();
            $forum_group_id = $canale->getForumGroupId();
        } else {
            $forum_attivo = 'N';
            /**
             * @todo testare se gli piace il valore NULL poi quotato nella query
             */
            $forum_forum_id = NULL ;
            $forum_group_id = NULL ;
        }

        $query = 'INSERT INTO canale (tipo_canale, nome_canale, immagine, visite, ultima_modifica, permessi_groups, files_attivo, news_attivo, forum_attivo, id_forum, group_id, links_attivo, files_studenti_attivo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        
        $result = $db->executeUpdate($query, array(
        		$canale->getTipoCanale(),
        		$canale->getNome(),
        		$canale->getImmagine(),
                $canale->getVisite(),
        		$canale->getUltimaModifica(),
        		$canale->getPermessi(),
        		$files_attivo,
        		$news_attivo,
        		$forum_attivo,
        		$forum_forum_id,
        		$forum_group_id,
        		$links_attivo,
        		$files_studenti_attivo,
        )) > 0;
        
        $canale->setIdCanale($db->lastInsertId('canale_id_canale_seq'));
        
        return $result;
    }

    public function update(Canale $canale)
    {
        $db = $this->getConnection();

        $files_attivo = ( $canale->getServizioFiles() ) ? 'S' : 'N';
        $news_attivo  = ( $canale->getServizioNews()  ) ? 'S' : 'N';
        $links_attivo = ( $canale->getServizioLinks() ) ? 'S' : 'N';
        $files_studenti_attivo = ( $canale->getServizioFilesStudenti() ) ? 'S' : 'N';
        if ( $canale->getServizioForum() ) {
            $forum_attivo = 'S';
            $forum_forum_id = $canale->getForumForumId();
            $forum_group_id = $canale->getForumGroupId();
        } else {
            $forum_attivo = 'N';

            $forum_forum_id = $canale->getForumForumId();
            $forum_group_id = $canale->getForumGroupId();
        }

        $query = 'UPDATE canale SET tipo_canale = ?'.
        ' , nome_canale = ?'.
        ' , immagine = ?'.
        ' , ultima_modifica = ?'.
        ' , permessi_groups = ?'.
        ' , files_attivo = ?'.
        ' , news_attivo = ?'.
        ' , forum_attivo = ?'.
        ' , id_forum = ?'.
        ' , group_id = ?'.
        ' , links_attivo = ?'.
        ' , files_studenti_attivo = ?'.
        ' WHERE id_canale =?';
        
        return $db->executeUpdate($query, array(
                $canale->getTipoCanale(),
                $canale->getNome(),
                $canale->getImmagine(),
                $canale->getUltimaModifica(),
                $canale->getPermessi(),
                $files_attivo,
                $news_attivo,
                $forum_attivo,
                $forum_forum_id,
                $forum_group_id,
                $links_attivo,
                $files_studenti_attivo,
                $canale->getIdCanale() 
        )) > 0;
    }

    public function idExists($id)
    {
        $db = $this->getConnection();

        $query = 'SELECT COUNT(id_canale) FROM canale WHERE id_canale = ?';
        $stmt = $db->executeQuery($query, array($id));
        
        return $stmt->fetchColumn() > 0;
    }
}
