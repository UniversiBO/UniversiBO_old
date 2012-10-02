<?php
namespace Universibo\Bundle\LegacyBundle\Entity\Files;

use \DB;
use Universibo\Bundle\LegacyBundle\Entity\Commenti\CommentoItem;
use Universibo\Bundle\LegacyBundle\Entity\DoctrineRepository;
use Universibo\Bundle\CoreBundle\Entity\UserRepository;

/**
 * DBNewsItem repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class FileItemStudentiRepository extends DoctrineRepository
{
    const ORDER_TITLE = 0;
    const ORDER_DATE_DESC = 1;
    const ORDER_RATING_DESC = 2;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var DBCanaleRepository
     */
    private $channelRepository;

    public function __construct(\DB_common $db, UserRepository $userRepository, DBCanaleRepository $channelRepository, $convert = false)
    {
        parent::__construct($db);

        $this->userRepository = $userRepository;
        $this->channelRepository = $channelRepository;
    }

    public function find($id)
    {
        $result = $this->findMany(array($id));

        return is_array($result) ? $result[0] : $result;
    }

    public function findAll($order)
    {
        $quale_ordine = '';
        $group = '';

        switch ($order) {
            case 0:
                $quale_ordine = 'A.titolo';
                break;
            case 1:
                $quale_ordine = 'A.data_inserimento DESC';
                break;
            case 2:
                $quale_ordine = 'avg(B.voto) DESC';
                $group = 'GROUP BY A.id_file';
                break;
        }
        $db = $this->getConnection();
        $query = 'SELECT A.id_file FROM file A, file_studente_commenti B' .
                ' WHERE A.id_file = B.id_file and A.eliminato != '.$db->quote(FileItem::ELIMINATO).
                ' AND B.eliminato != '.$db->quote(CommentoItem::ELIMINATO).
                ''.$group.' ORDER BY '.$quale_ordine;

        $res = $db->executeQuery($query);
        if (DB::isError($res)) {
            $this->throwError('_ERROR_DEFAULT',array('id_utente' => $this->sessionUser->getId(), 'msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }

        $rows = $res->rowCount();

        $id_files_studenti_list = array();

        while ( $res->fetchInto($row) ) {
            $id_files_studenti_list[]= $row[0];
        }

        $res->free();

        return $this->findMany($id_files_studenti_list);
    }

    public function findMany(array $ids)
    {
        $db = $this->getConnection();

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
        WHERE A.id_categoria = C.id_file_categoria AND id_tipo_file = D.id_file_tipo AND A.id_file  IN ('.$values.') AND eliminato!='.$db->quote(FILE_ELIMINATO);
        $res = & $db->executeQuery($query);

        //echo $query;

        if (DB :: isError($res)) {
            $this->throwError('_ERROR_CRITICAL', array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));
        }

        $rows = $res->rowCount();

        if ($rows == 0)
            return false;
        $files_list = array ();

        while (false !== ($row = $res->fetch())) {
            $username = $this->userRepository->getUsernameFromId($row[3]);
            $files_list[] = new FileItemStudenti($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $username , $row[15], $row[16], $row[17], $row[18]);
        }

        $res->free();

        return $files_list;
    }

    public function addToChannel(FileItemStudenti $file, $channelId)
    {
        if ($this->channelRepository->idExists($channelId)) {
            return false;
        }

        $db = $this->getConnection();

        $query = 'INSERT INTO file_studente_canale (id_file, id_canale) VALUES ('.$db->quote($file->getIdFile()).','.$db->quote($channelId).')';
        //? da testare il funzionamento di =
        $res = $db->executeQuery($query);
        if (DB :: isError($res)) {
            return false;
        }

        $ids = $file->getIdCanali();
        $ids[] = $channelId;
        $file->setIdCanali($ids);

        return true;
    }

    public function removeFromChannel(FileItemStudenti $file, $channelId)
    {
        $db = $this->getConnection();
        $query = 'DELETE FROM file_studente_canale WHERE id_canale='.$db->quote($channelId).' AND id_file='.$db->quote($file->getIdFile());

        $res = $db->executeQuery($query);

        if (DB::isError($res)) {
            $this->throwError('_ERROR_DEFAULT', array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));
        }
    }

    public function getChannelIds(FileItemStudenti $file)
    {
        $id_file = $file->getIdFile();

        $db = $this->getConnection();

        $query = 'SELECT id_canale FROM file_studente_canale WHERE id_file='.$db->quote($id_file);
        $res = $db->executeQuery($query);

        if (DB::isError($res)) {
            $this->throwError('_ERROR_DEFAULT', array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));
        }

        $res->fetchInto($row);

        $return = array($row[0]);

        return $return;
    }

    public function delete(FileItemStudenti $file)
    {
        $db = $this->getConnection();

        $query = 'UPDATE file SET eliminato  = '.$db->quote(FileItem::ELIMINATO).' WHERE id_file = '.$db->quote($file->getIdFile());
        $res = $db->executeQuery($query);
        if (DB::isError($res)) {
            $db->rollback();
            $this->throwError('_ERROR_CRITICAL', array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));
        }

        return false;
    }

    public function isFileStudenti($fileId)
    {
        $db = $this->getConnection();

        $query = 'SELECT count(id_file) FROM file_studente_canale WHERE id_file='.$db->quote($fileId).' GROUP BY id_file';
        $res = $db->executeQuery($query);

        if (DB::isError($res)) {
            $this->throwError('_ERROR_DEFAULT', array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));
        }
        $res->fetchInto($ris);

        return $ris[0] > 0;
    }

    public function getAverageRating($fileId)
    {
        $db = $this->getConnection();

        $query = 'SELECT avg(voto) FROM file_studente_commenti WHERE id_file='.$db->quote($fileId).' AND eliminato = '.$db->quote(CommentoItem::NOT_ELIMINATO).' GROUP BY id_file';
        $res = $db->executeQuery($query);

        if (DB::isError($res)) {
            $this->throwError('_ERROR_DEFAULT', array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));
        }
        $res->fetchInto($ris);

        return $ris[0];
    }

    public function deleteAllComments(FileItemStudenti $file)
    {
        $db = $this->getConnection();

        $query = 'UPDATE file_studente_commenti SET eliminato = '.$db->quote(CommentoItem::ELIMINATO).'WHERE id_file='.$db->quote($file->getIdFile());
        $res = $db->executeQuery($query);
        if (DB::isError($res)) {
            $db->rollback();
            $this->throwError('_ERROR_DEFAULT',array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }

        return true;
    }

    public function findIdByChannel($channelId)
    {
        $db = $this->getConnection();
        $query = 'SELECT A.id_file  FROM file A, file_studente_canale B
        WHERE A.id_file = B.id_file AND eliminato='.$db->quote( FileItem::NOT_ELIMINATO ).
        ' AND B.id_canale = '.$db->quote($channelId).' AND A.data_inserimento < '.$db->quote(time()).
        'ORDER BY A.id_categoria, A.data_inserimento DESC';
        $res = $db->executeQuery($query);

        if (DB::isError($res)) {
            $this->throwError('_ERROR_DEFAULT',array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }

        $id_file_list = array();

        while ( $res->fetchInto($row) ) {
            $id_file_list[]= $row[0];
        }

        $res->free();

        return $id_file_list;
    }
}
