<?php
namespace Universibo\Bundle\LegacyBundle\Entity\Files;

use Universibo\Bundle\LegacyBundle\PearDB\DB;
use Universibo\Bundle\LegacyBundle\Framework\Error;
use Universibo\Bundle\CoreBundle\Entity\MergeableRepositoryInterface;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\CoreBundle\Entity\UserRepository;
use Universibo\Bundle\LegacyBundle\Entity\DBCanaleRepository;
use Universibo\Bundle\LegacyBundle\Entity\DBRepository;
use Universibo\Bundle\LegacyBundle\PearDB\ConnectionWrapper;

/**
 * DBNewsItem repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBFileItemRepository extends DBRepository implements MergeableRepositoryInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var DBCanaleRepository
     */
    private $channelRepository;

    public function __construct(ConnectionWrapper $db, UserRepository $userRepository, DBCanaleRepository $channelRepository, $convert = false)
    {
        parent::__construct($db, $convert);

        $this->userRepository = $userRepository;
        $this->channelRepository = $channelRepository;
    }

    public function findByChannel($channelId)
    {
        $ids = $this->findIdByChannel($channelId);

        return is_array($ids) ? $this->findManyById($ids) : $ids;
    }

    public function countByChannel($channelId)
    {
        $db = $this->getDb();

        $query = 'SELECT count(A.id_file) FROM file A, file_canale B
        WHERE A.id_file = B.id_file AND eliminato!='
        . $db->quote(FileItem::ELIMINATO) . 'AND B.id_canale = '
        . $db->quote($id_canale) . '';
        $res = $db->getOne($query);

        if (DB::isError($res)) {
            $this->throwError('_ERROR_CRITICAL',
                    array('id_utente' => $this->sessionUser->getId(),
                            'msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
        }

        return $res;
    }

    public function findLatestByChannels(array $channelIds, $limit)
    {
        if (count($channelIds) === 0) {
            return array();
        }

        $db = $this->getDb();
        array_walk($channelIds, array($db, 'quote'));

        $values = implode(',', $channelIds);

        $query = 'SELECT A.id_file FROM file A, file_canale B
        WHERE A.id_file = B.id_file AND eliminato!='
        . $db->quote(FileItem::ELIMINATO) . 'AND B.id_canale IN ('
        . $values
        . ')
        ORDER BY A.data_inserimento DESC';
        $res = $db->limitQuery($query, 0, $limit);
        if (DB::isError($res)) {
            $this->throwError('_ERROR_DEFAULT',
                    array('id_utente' => $this->sessionUser->getId(),
                            'msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
        }

        $rows = $res->numRows();

        $ids = array();

        while ($res->fetchInto($row)) {
            $ids[] = $row[0];
        }

        $res->free();

        return $this->findManyById($ids);
    }

    public function countByUser(User $user)
    {
        $db = $this->getDb();

        $query = <<<EOT
SELECT COUNT(*)
    FROM file f
    WHERE f.id_utente = {$db->quote($user->getId())}
EOT;
        $res = $db->query($query);
        $row = $res->fetchRow();

        return intval($row[0]);
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

    public function find($id)
    {
        $result = $this->findManyById(array($id));

        return is_array($result) ? $result[0] : $result;
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
                . $values . ') AND eliminato=' . $db->quote(FileItem::NOT_ELIMINATO);

        $query .= ' ORDER BY C.id_file_categoria, data_inserimento DESC';

        $res = $db->query($query);

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

        $userRepo = $this->userRepository;

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

    public function findAll()
    {
        $db = $this->getDb();

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
        WHERE A.id_categoria = C.id_file_categoria AND id_tipo_file = D.id_file_tipo AND eliminato=' . $db->quote(FileItem::NOT_ELIMINATO);

        $query .= ' ORDER BY C.id_file_categoria, data_inserimento DESC';

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

        $userRepo = $this->userRepository;

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

    public function getKeyworkds($fileId)
    {
        $db = $this->getDb();

        $query = 'SELECT keyword FROM file_keywords WHERE id_file='.$db->quote($fileId);
        $res = $db->query($query);

        if (DB :: isError($res))
            Error :: throwError(_ERROR_DEFAULT, array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));

        $elenco_keywords = array ();

        while ($row = $this->fetchRow($res)) {
            $elenco_keywords[] = $row[0];
        }

        $res->free();

        return $elenco_keywords;
    }

    public function addKeyword($fileId, $keyword)
    {
        $db = $this->getDb();
        $query = 'INSERT INTO file_keywords(id_file, keyword) VALUES ('.$db->quote($fileId).' , '.$db->quote($keyword) .');';
        $res =  $db->query($query);

        if (DB :: isError($res)) {
            $this->throwError('_ERROR_DEFAULT', array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));
        }
    }

    public function removeKeyword($fileId, $keyword)
    {
        $db = $this->getDb();
        $query = 'DELETE FROM file_keywords WHERE id_file = '.$db->quote($fileId).' AND keyword = '.$db->quote($keyword);
        $res =$db->query($query);

        if (DB :: isError($res)) {
            $this->throwError('_ERROR_DEFAULT', array ('msg' => DB :: errorMessage($res), 'file' => __FILE__, 'line' => __LINE__));
        }
    }

    public function updateKeywords($fileId, array $keywords)
    {
        $old_elenco_keywords = $this->getKeyworkds($fileId);

        $db = $this->getDb();
        ignore_user_abort(1);
        $db->autoCommit(false);

        foreach ($keywords as $value) {
            if (!in_array($value, $old_elenco_keywords)) {
                $this->addKeyword($fileId, $value);
            }
        }

        foreach ($old_elenco_keywords as $value) {
            if (!in_array($value, $keywords)) {
                $this->removeKeyword($fileId, $value);
            }
        }

        $db->commit();

        $db->autoCommit(true);
        ignore_user_abort(0);
    }

    public function updateDownload(FileItem $file)
    {
        $db = $this->getDb();

        $query = 'UPDATE file SET download = ' . $db->quote($file->getDownLoad())
        . ' WHERE id_file = ' . $db->quote($file->getIdFile());
        $res = $db->query($query);
        if (DB::isError($res))
            $this->throwError('_ERROR_CRITICAL',
                    array('msg' => DB::errorMessage($res),
                            'file' => __FILE__, 'line' => __LINE__));
        $rows = $db->affectedRows();

        if ($rows == 1)
            return true;
        elseif ($rows == 0)
        return false;
        else
            $this->throwError('_ERROR_CRITICAL',
                    array(
                            'msg' => 'Errore generale database file non unico',
                            'file' => __FILE__, 'line' => __LINE__));
    }

    public function getTypes()
    {
        $db = $this->getDb();

        $query = 'SELECT id_file_tipo, descrizione FROM file_tipo';
        $res = $db->query($query);

        if (DB::isError($res))
            $this->throwError('_ERROR_DEFAULT',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

        $tipi = array();

        while ($row = $this->fetchRow($res)) {
            $tipi[$row[0]] = $row[1];
        }

        $res->free();

        return $tipi;
    }

    public function getTypeRegExps()
    {
        $db = $this->getDb();

        $query = 'SELECT id_file_tipo, pattern_riconoscimento FROM file_tipo';
        $res = $db->query($query);

        if (DB::isError($res)) {
            $this->throwError('_ERROR_DEFAULT',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
        }

        $tipi = array();

        while ($row = $this->fetchRow($res)) {
            $tipi[$row[0]] = $row[1];
        }

        $res->free();

        return $tipi;
    }

    public function getCategories()
    {
        $db = $this->getDb();
        $query = 'SELECT id_file_categoria, descrizione FROM file_categoria';
        $res = $db->query($query);

        if (DB::isError($res))
            $this->throwError('_ERROR_CRITICAL',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

        $categorie = array();

        while ($row = $this->fetchRow($res)) {
            $categorie[$row[0]] = $row[1];
        }

        $res->free();

        return $categorie;
    }

    public function findByUserId($userId, $order = false)
    {
        $db = $this->getDb();

        $query = 'SELECT id_file, permessi_download, permessi_visualizza, A.id_utente, titolo,
        A.descrizione, data_inserimento, data_modifica, dimensione, download,
        nome_file, A.id_categoria, id_tipo_file, hash_file, A.password,
        C.descrizione, D.descrizione, D.icona, D.info_aggiuntive
        FROM file A, file_categoria C, file_tipo D
        WHERE A.id_categoria = C.id_file_categoria AND id_tipo_file = D.id_file_tipo AND  eliminato ='
        . $db->quote(FileItem::NOT_ELIMINATO) . ' AND id_utente = '
        . $db->quote($userId)
        . ($order ? ' ORDER BY data_inserimento DESC' : '');
        $res = $db->query($query);

        //echo $query;

        if (DB::isError($res)) {
            $this->throwError('_ERROR_CRITICAL',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
        }

        $rows = $res->numRows();

        if ($rows == 0)
            return false;
        $files_list = array();

        while ($row = $this->fetchRow($res)) {
            $username = $this->userRepository->getUsernameFromId($row[3]);
            $files_list[] = new FileItem($row[0], $row[1], $row[2], $row[3],
                    $row[4], $row[5], $row[6], $row[7], $row[8], $row[9],
                    $row[10], $row[11], $row[12], $row[13], $row[14],
                    $username, $row[15], $row[16], $row[17], $row[18]);
        }

        $res->free();

        return $files_list;
    }

    public function insert(FileItem $file)
    {
        $db = $this->getDb();

        $db->autoCommit(false);
        $next_id = $db->nextID('file_id_file');
        $file->setIdFile($next_id);
        $return = true;
        $eliminata = FileItem::NOT_ELIMINATO;
        $query = 'INSERT INTO file (id_file, permessi_download, permessi_visualizza, id_utente, titolo,
        descrizione, data_inserimento, data_modifica, dimensione, download,
        nome_file, id_categoria, id_tipo_file, hash_file, password, eliminato) VALUES '
        . '( ' . $next_id . ' , '
        . $db->quote($file->getPermessiDownload()) . ' , '
        . $db->quote($file->getPermessiVisualizza()) . ' , '
        . $db->quote($file->getIdUtente()) . ' , '
        . $db->quote($file->getTitolo()) . ' , '
        . $db->quote($file->getDescrizione()) . ' , '
        . $db->quote($file->getDataInserimento()) . ' , '
        . $db->quote($file->getDataModifica()) . ' , '
        . $db->quote($file->getDimensione()) . ' , '
        . $db->quote($file->getDownload()) . ' , '
        . $db->quote($file->getRawNomeFile()) . ' , '
        . $db->quote($file->getIdCategoria()) . ' , '
        . $db->quote($file->getIdTipoFile()) . ' , '
        . $db->quote($file->getHashFile()) . ' , '
        . $db->quote($file->getPassword()) . ' , '
        . $db->quote(FileItem::NOT_ELIMINATO) . ' )';

        $res = $db->query($query);

        if (DB::isError($res)) {
            $db->rollback();
            $this->throwError('_ERROR_CRITICAL',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
        }

        $db->commit();
        $db->autoCommit(true);
    }

    public function update(FileItem $file)
    {
        $db = $this->getDb();

        $db->autoCommit(false);
        $return = true;
        //$scadenza = ($this->getDataScadenza() == NULL) ? ' NULL ' : $db->quote($this->getDataScadenza());
        //$flag_urgente = ($this->isUrgente()) ? NEWS_URGENTE : NEWS_NOT_URGENTE;
        //$deleted = ($this->isEliminata()) ? NEWS_ELIMINATA : NEWS_NOT_ELIMINATA;
        $query = 'UPDATE file SET id_file = ' . $db->quote($file->getIdFile())
        . ' , permessi_download = '
        . $db->quote($file->getPermessiDownload())
        . ' , permessi_visualizza = '
        . $db->quote($file->getPermessiVisualizza())
        . ' , id_utente = ' . $db->quote($file->getIdUtente())
        . ' , titolo = ' . $db->quote($file->getTitolo())
        . ' , descrizione = ' . $db->quote($file->getDescrizione())
        . ' , data_inserimento = '
        . $db->quote($file->getDataInserimento())
        . ' , data_modifica = ' . $db->quote($file->getDataModifica())
        . ' , dimensione = ' . $db->quote($file->getDimensione())
        . ' , download = ' . $db->quote($file->getDownload())
        . ' , nome_file = ' . $db->quote($file->getRawNomeFile())
        . ' , id_categoria = ' . $db->quote($file->getIdCategoria())
        . ' , id_tipo_file = ' . $db->quote($file->getIdTipoFile())
        . ' , hash_file = ' . $db->quote($file->getHashFile())
        . ' , password = ' . $db->quote($file->getPassword())
        . ' WHERE id_file = ' . $db->quote($file->getIdFile());
        //echo $query;
        $res = $db->query($query);
        //var_dump($query);
        if (DB::isError($res)) {
            $db->rollback();
            $this->throwError('_ERROR_CRITICAL',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
            $return = false;
        }

        $db->commit();
        $db->autoCommit(true);
    }

    public function getChannelIds(FileItem $file)
    {
        $id_file = $file->getIdFile();

        $db = $this->getDb();

        $where = 'WHERE id_file='. $db->quote($id_file);
        $query = 'SELECT id_canale FROM file_canale '.$where;
        $query .= 'UNION SELECT id_canale FROM file_studente_canale '.$where;

        $res = $db->query($query);

        if (DB::isError($res)) {
            $this->throwError('_ERROR_DEFAULT',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
        }

        $elenco_id_canale = array();

        while ($res->fetchInto($row)) {
            $elenco_id_canale[] = $row[0];
        }
        $res->free();

        sort($elenco_id_canale);

        return $elenco_id_canale;
    }

    public function addToChannel(FileItem $file, $channelId)
    {
        $db = $this->getDb();

        if (!$this->channelRepository->idExists($channelId)) {
            return false;
        }

        $query = 'INSERT INTO file_canale (id_file, id_canale) VALUES ('
        . $db->quote($file->getIdFile()) . ',' . $db->quote($channelId)
        . ')';

        $this->getConnection()->executeUpdate($query);
        $ids = $file->getIdCanali();
        $ids[] = $channelId;
        $file->setIdCanali($ids);

        return true;
    }

    public function removeFromChannel(FileItem $file, $channelId)
    {
        $db = $this->getDb();

        $query = 'DELETE FROM file_canale WHERE id_canale='
        . $db->quote($channelId) . ' AND id_file='
        . $db->quote($file->getIdFile());
        //? da testare il funzionamento di =
        $res = $db->query($query);

        if (DB::isError($res)) {
            $this->throwError('_ERROR_DEFAULT',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
        }

        $file->setIdCanali($ids = array_diff($file->getIdCanali(),array($channelId)));
        if (count($ids) === 0) {
            $this->delete($file);
        }
    }

    public function delete(FileItem $file)
    {
        $lista_canali = $this->getChannelIds($file);

        if (count($lista_canali) == 0) {
            $db = $this->getDb();

            $query = 'UPDATE file SET eliminato  = '
            . $db->quote(FileItem::ELIMINATO) . ' WHERE id_file = '
            . $db->quote($file->getIdFile());
            //echo $query;
            $res = $db->query($query);
            //var_dump($query);
            if (DB::isError($res)) {
                $db->rollback();
                $this->throwError('_ERROR_CRITICAL',
                        array('msg' => DB::errorMessage($res),
                                'file' => __FILE__, 'line' => __LINE__));
            }

            return true;
        }

        return false;
    }

    public function transferOwnership(User $source, User $target)
    {
        $db = $this->getDb();

        $query = <<<EOT
UPDATE file
    SET id_utente = {$target->getId()}
    WHERE id_utente = {$source->getId()}
EOT;

        $res = $db->query($query);
        if (DB::isError($res)) {
            $this->throwError('_ERROR_CRITICAL',array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }

        return $db->affectedRows();
    }
}
