<?php

namespace Universibo\Bundle\LegacyBundle\Entity;

use Universibo\Bundle\LegacyBundle\PearDB\DB;
use Universibo\Bundle\LegacyBundle\Framework\Error;

/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBCanaleRepository extends DBRepository
{
    public function getTipoCanaleFromId($id_canale)
    {
        $db = $this->getDb();

        $query = 'SELECT tipo_canale FROM canale WHERE id_canale= '.$db->quote($id_canale);
        $res = $db->query($query);
        if (DB::isError($res))
            $this->throwError('_ERROR_CRITICAL',array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

        $rows = $res->numRows();
        if( $rows > 1) $this->throwError('_ERROR_CRITICAL',array('msg'=>'Errore generale database: canale non unico','file'=>__FILE__,'line'=>__LINE__));
        if( $rows == 0) return false;

        $row = $this->fetchRow($res);

        return $row[0];
    }

    public function updateUltimaModifica(Canale $canale)
    {
        $db = $this->getDb();

        $query = 'UPDATE canale SET ultima_modifica = '.$db->quote($canale->getUltimaModifica()).' WHERE id_canale = '.$db->quote($canale->getIdCanale());
        $res = $db->query($query);
        if (DB::isError($res))
            $this->throwError('_ERROR_CRITICAL',array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        $rows = $db->affectedRows();

        if( $rows == 1) return true;
        elseif( $rows == 0) return false;
        else $this->throwError('_ERROR_CRITICAL',array('msg'=>'Errore generale database canali: id non unico','file'=>__FILE__,'line'=>__LINE__));
        return false;
    }

    public function find($idCanale)
    {
        $result = $this->findManyById(array($idCanale));

        return is_array($result) ? $result[0] : $result;
    }

    public function findManyById(array $idCanale)
    {
        if (count($idCanale) === 0) {
            return array();
        }

        $db = $this->getDb();

        array_walk($idCanale, array($db, 'quote'));
        $canali_comma = implode (' , ',$idCanale);

        $query = 'SELECT tipo_canale, nome_canale, immagine, visite, ultima_modifica, permessi_groups, files_attivo, news_attivo, forum_attivo, id_forum, group_id, links_attivo, id_canale, files_studenti_attivo FROM canale WHERE id_canale IN ('.$canali_comma.') ORDER BY nome_canale;';
        $res = $db->query($query);
        if (DB::isError($res))
            $this->throwError('_ERROR_CRITICAL',array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

        $rows = $res->numRows();
        if( $rows > count($idCanale)) Error::throwError(_ERROR_CRITICAL,array('msg'=>'Errore generale database: canale non unico','file'=>__FILE__,'line'=>__LINE__));
        if( $rows == 0) return false;

        $elenco_canali = array();
        while ($row = $this->fetchRow($res)) {
            //var_dump($row);
            $elenco_canali[] = new Canale($row[12], $row[5], $row[4], $row[0], $row[2], $row[1], $row[3],
                    $row[7]=='S', $row[6]=='S', $row[8]=='S', $row[9], $row[10], $row[11]=='S',$row[13]=='S' );
        }
        $res->free();

        return $elenco_canali;
    }

    /**
     * Finds channel ids given a type
     *
     * @param  integer   $type
     * @return integer[]
     */
    public function findManyByType($type)
    {
        $db = $this->getConnection();

        $query = 'SELECT id_canale FROM canale WHERE tipo_canale = '.$db->quote($type);
        $res = $db->executeQuery($query);

        $elenco_canali = array();
        while (false !== ($id = $res->fetchColumn())) {
            $elenco_canali[] = $id;
        }

        return $elenco_canali;
    }

    public function addVisite(Canale $canale, $increment = 1)
    {
        $db = $this->getDb();

        $query = 'UPDATE canale SET visite = visite + '.$db->quote($increment).' WHERE id_canale = '.$db->quote($canale->getIdCanale());
        $res = $db->query($query);
        if (DB::isError($res))
            $this->throwError('_ERROR_CRITICAL',array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        $rows = $db->affectedRows();

        if ($rows == 1) {
            $ok = true;
        } elseif ($rows == 0) {
            $ok = false;
        } else {
            $this->throwError('_ERROR_CRITICAL',array('msg'=>'Errore generale database: canale non unico','file'=>__FILE__,'line'=>__LINE__));
        }

        if ($ok) {
            $query = 'SELECT visite FROM canale WHERE id_canale = '.$db->quote($canale->getIdCanale());
            $res = $db->query($query);
            $row = $this->fetchRow($res);

            if (DB::isError($res)) {
                $this->throwError('_ERROR_CRITICAL',array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
            }

            $canale->setVisite((int) $row[0]);
        }

        return $ok;
    }

    public function insert(Canale $canale)
    {
        $db = $this->getDb();

        $canale->setIdCanale($db->nextID('canale_id_canale'));
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

        $query = 'INSERT INTO canale (id_canale, tipo_canale, nome_canale, immagine, visite, ultima_modifica, permessi_groups, files_attivo, news_attivo, forum_attivo, id_forum, group_id, links_attivo, files_studenti_attivo) VALUES ('.
                $db->quote($canale->getIdCanale()).' , '.
                $db->quote($canale->getTipoCanale()).' , '.
                $db->quote($canale->getNomeCanale()).' , '.
                $db->quote($canale->getImmagine()).' , '.
                $db->quote($canale->getVisite()).' , '.
                $db->quote($canale->getUltimaModifica()).' , '.
                $db->quote($canale->getPermessi()).' , '.
                $db->quote($files_attivo).' , '.
                $db->quote($news_attivo).' , '.
                $db->quote($forum_attivo).' , '.
                $db->quote($forum_forum_id).' , '.
                $db->quote($forum_group_id).' , '.
                $db->quote($links_attivo).' ,'.
                $db->quote($files_studenti_attivo).' )';
        $res = $db->query($query);
        if (DB::isError($res)) {
            Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

            return false;
        }

        return true;
    }

    public function update(Canale $canale)
    {
        $db = $this->getDb();

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

        $query = 'UPDATE canale SET tipo_canale = '.$db->quote($canale->getTipoCanale()).
        ' , nome_canale = '.$db->quote($canale->getNome()).   //<--attento
        ' , immagine = '.$db->quote($canale->getImmagine()).
        ' , ultima_modifica = '.$db->quote($canale->getUltimaModifica()).
        ' , permessi_groups = '.$db->quote($canale->getPermessi()).
        ' , files_attivo = '.$db->quote($files_attivo).
        ' , news_attivo = '.$db->quote($news_attivo).
        ' , forum_attivo = '.$db->quote($forum_attivo).
        ' , id_forum = '.$db->quote($forum_forum_id).
        ' , group_id = '.$db->quote($forum_group_id).
        ' , links_attivo = '.$db->quote($links_attivo).
        ' , files_studenti_attivo = '.$db->quote($files_studenti_attivo).' WHERE id_canale ='.$db->quote($canale->getIdCanale());

        $res = $db->query($query);
        if (DB::isError($res))
            $this->throwError('_ERROR_CRITICAL',array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        $rows = $db->affectedRows();

        if( $rows == 1) return true;
        elseif( $rows == 0) return false;
        else $this->throwError('_ERROR_CRITICAL',array('msg'=>'Errore generale database: canale non unico','file'=>__FILE__,'line'=>__LINE__));
    }

    public function idExists($id)
    {
        $db = $this->getDb();

        $query = 'SELECT id_canale FROM canale WHERE id_canale = '.$db->quote($id).';';
        $res = $db->query($query);
        if (DB::isError($res))
            $this->throwError('_ERROR_CRITICAL',array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

        return $res->numRows() == 1;
    }
}
