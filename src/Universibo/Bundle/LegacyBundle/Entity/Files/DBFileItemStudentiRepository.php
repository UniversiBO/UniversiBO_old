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
}
