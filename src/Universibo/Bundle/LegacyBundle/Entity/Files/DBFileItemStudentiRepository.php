<?php
namespace Universibo\Bundle\LegacyBundle\Entity\Files;

use \DB;
use \Error;
use Universibo\Bundle\LegacyBundle\Entity\DBCanaleRepository;
use Universibo\Bundle\LegacyBundle\Entity\DBRepository;
use Universibo\Bundle\LegacyBundle\Entity\DBUserRepository;

/**
 * DBNewsItem repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBFileItemStudentiRepository extends DBRepository
{
    /**
     * @var DBUserRepository
     */
    private $userRepository;

    /**
     * @var DBCanaleRepository
     */
    private $channelRepository;

    public function __construct(\DB_common $db, DBUserRepository $userRepository, DBCanaleRepository $channelRepository, $convert = false)
    {
        parent::__construct($db, $convert);

        $this->userRepository = $userRepository;
        $this->channelRepository = $channelRepository;
    }

    public function find($id)
    {
        $result = $this->findMany(array($id));

        return is_array($result) ? $result[0] : $result;
    }

    public function findMany(array $ids)
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
        WHERE A.id_categoria = C.id_file_categoria AND id_tipo_file = D.id_file_tipo AND A.id_file  IN ('.$values.') AND eliminato!='.$db->quote(FILE_ELIMINATO);
        $res = & $db->query($query);

        //echo $query;

        if (DB :: isError($res))
            Error :: throwError(_ERROR_CRITICAL, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));

        $rows = $res->numRows();

        if ($rows == 0)

            return false;
        $files_list = array ();

        while ($row = $this->fetchRow($res)) {
            $username = $this->userRepository->getUsernameFromId($row[3]);
            $files_list[] = new FileItemStudenti($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12], $row[13], $row[14], $username , $row[15], $row[16], $row[17], $row[18]);
        }

        $res->free();

        return $files_list;
    }

    public function addToChannel(FileItemStudenti $file, $channelId)
    {
        if ($this->channelRepository->exists($channelId)) {
            return false;
        }

        $db = $this->getDb();

        $query = 'INSERT INTO file_studente_canale (id_file, id_canale) VALUES ('.$db->quote($file->getIdFile()).','.$db->quote($channelId).')';
        //? da testare il funzionamento di =
        $res = $db->query($query);
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
        $query = 'DELETE FROM file_studente_canale WHERE id_canale='.$db->quote($channelId).' AND id_file='.$db->quote($file->getIdFile());

        $res = $db->query($query);

        if (DB::isError($res)) {
            $this->throwError('_ERROR_DEFAULT', array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));
        }
    }

    public function getChannelIds(FileItemStudenti $file)
    {
        $id_file = $file->getIdFile();

        $db = $this->getDb();

        $query = 'SELECT id_canale FROM file_studente_canale WHERE id_file='.$db->quote($id_file);
        $res = $db->query($query);

        if (DB::isError($res)) {
            $this->throwError('_ERROR_DEFAULT', array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));
        }

        $res->fetchInto($row);

        $return = array($row[0]);

        return $return;
    }

    public function delete(FileItemStudenti $file)
    {
        $db = $this->getDb();

        $query = 'UPDATE file SET eliminato  = '.$db->quote(FileItem::ELIMINATO).' WHERE id_file = '.$db->quote($file->getIdFile());
        $res = $db->query($query);
        if (DB::isError($res)) {
            $db->rollback();
            $this->throwError('_ERROR_CRITICAL', array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));
        }

        return false;
    }

    public function isFileStudenti($fileId)
    {
        $db = $this->getDb();

        $query = 'SELECT count(id_file) FROM file_studente_canale WHERE id_file='.$db->quote($fileId).' GROUP BY id_file';
        $res = $db->query($query);

        if (DB::isError($res)) {
            $this->throwError('_ERROR_DEFAULT', array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));
        }
        $res->fetchInto($ris);

        return $ris[0]==0;
    }

    public function getAverageRating($fileId)
    {
        $db = $this->getDb();

        $query = 'SELECT avg(voto) FROM file_studente_commenti WHERE id_file='.$db->quote($fileId).' AND eliminato = '.$db->quote(CommentoItem::NOT_ELIMINATO).' GROUP BY id_file';
        $res = $db->query($query);

        if (DB::isError($res)) {
            $this->throwError('_ERROR_DEFAULT', array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));
        }
        $res->fetchInto($ris);

        return $ris[0];
    }

    public function deleteAllComments(FileItemStudenti $file)
    {
        $db = $this->getDb();

        $query = 'UPDATE file_studente_commenti SET eliminato = '.$db->quote(CommentoItem::ELIMINATO).'WHERE id_file='.$db->quote($file->getIdFile());
        $res = $db->query($query);
        if (DB::isError($res)) {
            $db->rollback();
            $this->throwError('_ERROR_DEFAULT',array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }

        return true;
    }
}
