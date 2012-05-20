<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity\Files;
use UniversiBO\Bundle\LegacyBundle\Entity\DBUserRepository;

use \DB;
use UniversiBO\Bundle\LegacyBundle\Entity\DBRepository;

/**
 * DBNewsItem repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBFileItemRepository extends DBRepository
{
    public function findByChannel($channelId)
    {
        return $this->findManyById($this->findIdByChannel($channelId));
    }
    
    public function findIdByChannel($channelId)
    {
        $db = $this->getDb();

        $query = 'SELECT A.id_file  FROM file A, file_canale B
        WHERE A.id_file = B.id_file AND eliminato='
                . $db->quote(FileItem::NOT_ELIMINATO) . ' AND B.id_canale = '
                . $db->quote($channelId) . ' AND A.data_inserimento < '
                . $db->quote(time())
                . 'ORDER BY A.id_categoria, A.data_inserimento DESC';
        $res = $db->query($query);

        if (DB::isError($res))
            $this
                    ->throwError('_ERROR_DEFAULT',
                            array('msg' => DB::errorMessage($res),
                                    'file' => __FILE__, 'line' => __LINE__));

        $id_file_list = array();

        while ($row = $this->fetchRow($res)) {
            $id_file_list[] = $row[0];
        }

        $res->free();

        return $id_file_list;
    }

    public function findManyById(array $ids)
    {
        $db = $this->getDb();

        if (count($ids) == 0) {
            return array();
        }

        //esegue $db->quote() su ogni elemento dell'array
        //array_walk($id_notizie, array($db, 'quote'));
        if (count($ids) == 1)
            $values = $ids[0];
        else
            $values = implode(',', $ids);

        //		$query = 'SELECT id_file, permessi_download, permessi_visualizza, A.id_utente, titolo,
        //						 A.descrizione, data_inserimento, data_modifica, dimensione, download,
        //						 nome_file, A.id_categoria, id_tipo_file, hash_file, A.password,
        //						 username, C.descrizione, D.descrizione, D.icona, D.info_aggiuntive
        //						 FROM file A, utente B, file_categoria C, file_tipo D
        //						 WHERE A.id_utente = B.id_utente AND A.id_categoria = C.id_file_categoria AND id_tipo_file = D.id_file_tipo AND A.id_file  IN ('.$values.') AND eliminato!='.$db->quote(FILE_ELIMINATO);
        $query = 'SELECT id_file, permessi_download, permessi_visualizza, A.id_utente, titolo,
        A.descrizione, data_inserimento, data_modifica, dimensione, download,
        nome_file, A.id_categoria, id_tipo_file, hash_file, A.password,
        C.descrizione, D.descrizione, D.icona, D.info_aggiuntive
        FROM file A, file_categoria C, file_tipo D
        WHERE A.id_categoria = C.id_file_categoria AND id_tipo_file = D.id_file_tipo AND A.id_file  IN ('
                . $values . ') AND eliminato!=' . $db->quote(FILE_ELIMINATO);
        $res = &$db->query($query);

        //echo $query;

        if (DB::isError($res)) {
            $this
                    ->throwError('_ERROR_CRITICAL',
                            array('msg' => DB::errorMessage($res),
                                    'file' => __FILE__, 'line' => __LINE__));
        }

        $rows = $res->numRows();

        if ($rows == 0)

            return false;
        $files_list = array();

        $userRepo = new DBUserRepository($db);
        
        while ($row = $this->fetchRow($res)) {
            $username = $userRepo->getUsernameFromId($row[3]); 
            
            $files_list[] = new FileItem($row[0], $row[1], $row[2], $row[3],
                    $row[4], $row[5], $row[6], $row[7], $row[8], $row[9],
                    $row[10], $row[11], $row[12], $row[13], $row[14],
                    $username, $row[15], $row[16], $row[17], $row[18]);
        }

        $res->free();

        return $files_list;
    }
}
