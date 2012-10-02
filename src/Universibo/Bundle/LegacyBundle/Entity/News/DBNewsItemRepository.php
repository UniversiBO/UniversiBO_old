<?php
namespace Universibo\Bundle\LegacyBundle\Entity\News;

use Universibo\Bundle\LegacyBundle\Entity\DBCanaleRepository;

use \DB;
use Universibo\Bundle\LegacyBundle\Entity\DoctrineRepository;
use Universibo\Bundle\CoreBundle\Entity\UserRepository;

/**
 * DBNewsItem repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBNewsItemRepository extends DoctrineRepository
{
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
        parent::__construct($db, $convert);

        $this->userRepository = $userRepository;
        $this->channelRepository = $channelRepository;
    }

    public function find($id)
    {
        $result = $this->findMany(array($id));

        return is_array($result) ? $result[0] : $result;
    }

    public function findAll()
    {
        $db = $this->getDb();

        //		$query = 'SELECT titolo, notizia, data_inserimento, data_scadenza, flag_urgente, eliminata, A.id_utente, id_news, username, data_modifica FROM news A, utente B WHERE A.id_utente = B.id_utente AND id_news IN ('.$values.') AND eliminata!='.$db->quote(NewsItem::ELIMINATA);
        $query = 'SELECT titolo, notizia, data_inserimento, data_scadenza, flag_urgente, eliminata, A.id_utente, id_news, data_modifica FROM news A WHERE eliminata='
                . $db->quote(NewsItem::NOT_ELIMINATA)
                . ' ORDER BY data_inserimento DESC';
        //var_dump($query);
        $res = $db->query($query);

        if (DB::isError($res)) {
            $this
                    ->throwError('_ERROR_CRITICAL',
                            array('msg' => DB::errorMessage($res),
                                    'file' => __FILE__, 'line' => __LINE__));
        }

        $rows = $res->numRows();

        if ($rows == 0)
            return false;
        $news_list = array();

        while ($row = $this->fetchRow($res)) {
            $userRepository = $this->userRepository;
            $username = $userRepository->getUsernameFromId($row[6]);

            $news_list[] = new NewsItem($row[7], $row[0], $row[1], $row[2],
                    $row[3], $row[8], ($row[4] == NewsItem::URGENTE),
                    ($row[5] == NewsItem::ELIMINATA), $row[6], $username);
        }

        $res->free();

        return $news_list;
    }

    public function findMany(array $ids)
    {
        if (count($ids) === 0) {
            return array();
        }

        $db = $this->getDb();

        array_walk($ids, 'intval');
        $values = implode(',', $ids);

        //		$query = 'SELECT titolo, notizia, data_inserimento, data_scadenza, flag_urgente, eliminata, A.id_utente, id_news, username, data_modifica FROM news A, utente B WHERE A.id_utente = B.id_utente AND id_news IN ('.$values.') AND eliminata!='.$db->quote(NewsItem::ELIMINATA);
        $query = 'SELECT titolo, notizia, data_inserimento, data_scadenza, flag_urgente, eliminata, A.id_utente, id_news, data_modifica FROM news A WHERE id_news IN ('
                . $values . ') AND eliminata='
                . $db->quote(NewsItem::NOT_ELIMINATA)
                . ' ORDER BY data_inserimento DESC';
        //var_dump($query);
        $res = $db->query($query);

        if (DB::isError($res)) {
            $this
                    ->throwError('_ERROR_CRITICAL',
                            array('msg' => DB::errorMessage($res),
                                    'file' => __FILE__, 'line' => __LINE__));
        }

        $rows = $res->numRows();

        if ($rows == 0)
            return false;
        $news_list = array();

        while ($row = $this->fetchRow($res)) {
            $userRepository = $this->userRepository;
            $username = $userRepository->getUsernameFromId($row[6]);

            $news_list[] = new NewsItem($row[7], $row[0], $row[1], $row[2],
                    $row[3], $row[8], ($row[4] == NewsItem::URGENTE),
                    ($row[5] == NewsItem::ELIMINATA), $row[6], $username);
        }

        $res->free();

        return $news_list;
    }

    public function findByCanale($id, $limit = null, $expired = false)
    {
        $db = $this->getDb();

        $sql = '';
        $sql .= 'SELECT n.titolo, ';
        $sql .= '       n.notizia, ';
        $sql .= '       n.data_inserimento, ';
        $sql .= '       n.data_scadenza, ';
        $sql .= '       n.flag_urgente, ';
        $sql .= '       n.eliminata, ';
        $sql .= '       u.username, ';
        $sql .= '       n.id_news, ';
        $sql .= '       n.data_modifica, ';
        $sql .= '       n.id_utente ';
        $sql .= '    FROM news n';
        $sql .= '    INNER JOIN news_canale nc';
        $sql .= '        ON nc.id_news = n.id_news';
        $sql .= '    INNER JOIN utente u';
        $sql .= '        ON u.id_utente = n.id_utente';
        $sql .= '    WHERE nc.id_canale = ' . $db->quote($id);
        $sql .= '        AND n.eliminata = '
                . $db->quote(NewsItem::NOT_ELIMINATA);
        $sql .= '        AND n.data_inserimento <= '
                . $db->quote($now = time());

        if (!$expired) {
            $sql .= '    AND (n.data_scadenza >= ' . $db->quote($now)
                    . ' OR n.data_scadenza IS NULL)';
        }

        $sql .= '    ORDER BY data_inserimento DESC';

        if (is_int($limit)) {
            $sql .= ' LIMIT ' . intval($limit);
        }

        $res = $db->query($sql);

        if (DB::isError($res)) {
            $this
                    ->throwError('_ERROR_DEFAULT',
                            array('msg' => DB::errorMessage($res),
                                    'file' => __FILE__, 'line' => __LINE__));
        }

        $news = array();
        while ($row = $this->fetchRow($res)) {
            $userRepository = $this->userRepository;

            $news[] = new NewsItem($row[7], $row[0], $row[1], $row[2], $row[3],
                    $row[8], ($row[4] == NewsItem::URGENTE),
                    ($row[5] == NewsItem::ELIMINATA), $row[9], $row[6]);
        }

        $res->free();

        return $news;
    }

    public function insert(NewsItem $newsItem)
    {
        $db = $this->getDb();

        ignore_user_abort(1);
        $db->autoCommit(false);
        $next_id = $db->nextID('news_id_news');
        $return = true;
        $scadenza = ($newsItem->getDataScadenza() == NULL) ? ' NULL '
                : $db->quote($newsItem->getDataScadenza());
        $eliminata = ($newsItem->isEliminata()) ? NewsItem::ELIMINATA
                : NewsItem::NOT_ELIMINATA;
        $flag_urgente = ($newsItem->isUrgente()) ? NewsItem::URGENTE
                : NewsItem::NOT_URGENTE;
        $query = 'INSERT INTO news (id_news, titolo, data_inserimento, data_scadenza, notizia, id_utente, eliminata, flag_urgente, data_modifica) VALUES '
                . '( ' . $next_id . ' , ' . $db->quote($newsItem->getTitolo())
                . ' , ' . $db->quote($newsItem->getDataIns()) . ' , '
                . $scadenza . ' , ' . $db->quote($newsItem->getNotizia())
                . ' , ' . $db->quote($newsItem->getIdUtente()) . ' , '
                . $db->quote($eliminata) . ' , ' . $db->quote($flag_urgente)
                . ' , ' . $db->quote($newsItem->getUltimaModifica()) . ' )';
        $res = $db->query($query);
        //var_dump($query);
        if (DB::isError($res)) {
            $db->rollback();
            $this
                    ->throwError('_ERROR_CRITICAL',
                            array('msg' => DB::errorMessage($res),
                                    'file' => __FILE__, 'line' => __LINE__));
        }

        $newsItem->setIdNotizia($next_id);

        $db->commit();
        $db->autoCommit(true);
        ignore_user_abort(0);

        return $return;
    }

    public function getChannelIdList(NewsItem $news)
    {
        $db = $this->getDb();

        $query = 'SELECT id_canale FROM news_canale WHERE id_news='.$db->quote($news->getIdNotizia()).' ORDER BY id_canale';
        $res = $db->query($query);

        if (DB::isError($res)) {
            $this->throwError('_ERROR_DEFAULT',array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }

        $elenco_id_canale = array();

        while ($row = $this->fetchRow($res)) {
            $elenco_id_canale[] = $row[0];
        }

        $res->free();

        return $elenco_id_canale;
    }

    public function removeFromChannel(NewsItem $news, $channelId)
    {
        $db = $this->getDb();

        $query = 'DELETE FROM news_canale WHERE id_canale='.$db->quote($channelId).' AND id_news='.$db->quote($news->getIdNotizia());
        $res = $db->query($query);

        if (DB::isError($res)) {
            $this->throwError('_ERROR_DEFAULT',array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }

        $news->setIdCanali($ids = array_diff ($this->elencoIdCanali, array($id_canale)));

        if (count($ids) === 0) {
            $this->delete($news);
        }
    }

    public function delete(NewsItem $news)
    {
        $news->setEliminata(true);
        $this->update($news);
    }

    public function addToChannel(NewsItem $news, $channelId)
    {
        if (!$this->channelRepository->idExists($channelId)) {
            return false;
        }

        $db = $this->getDb();
        $query = 'INSERT INTO news_canale (id_news, id_canale) VALUES ('.$db->quote($news->getIdNotizia()).','.$db->quote($channelId).')';

        $res = $db->query($query);
        if (DB::isError($res)) {
            return false;
        }

        $ids = $news->getIdCanali();
        $ids[] = $channelId;
        $news->setIdCanali($ids);

        return true;
    }

    public function update(NewsItem $news)
    {
        $db = $this->getDb();

        $db->autoCommit(false);
        $return = true;
        $scadenza = ($news->getDataScadenza() == NULL) ? ' NULL ' : $db->quote($news->getDataScadenza());
        $flag_urgente = ($news->isUrgente()) ? NewsItem::URGENTE : NewsItem::NOT_URGENTE;
        $deleted = ($news->isEliminata()) ? NewsItem::ELIMINATA : NewsItem::NOT_ELIMINATA;
        $query = 'UPDATE news SET titolo = '.$db->quote($news->getTitolo())
        .' , data_inserimento = '.$db->quote($news->getDataIns())
        .' , data_scadenza = '.$scadenza
        .' , notizia = '.$db->quote($news->getNotizia())
        .' , id_utente = '.$db->quote($news->getIdUtente())
        .' , eliminata = '.$db->quote($deleted)
        .' , flag_urgente = '.$db->quote($flag_urgente)
        .' , data_modifica = '.$db->quote($news->getUltimaModifica())
        .' WHERE id_news = '.$db->quote($news->getIdNotizia());
        //echo $query;
        $res = $db->query($query);
        //var_dump($query);
        if (DB::isError($res)) {
            $db->rollback();
            $this->throwError('_ERROR_CRITICAL',array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }

        $db->commit();
        $db->autoCommit(true);
    }

    public function findLatestByChannel($channelId, $limit, $offset = 0)
    {
        return $this->findLatestByChannels(array($channelId), $limit, $offset);
    }

    public function findLatestByChannels(array $channelIds, $limit, $offset = 0)
    {
        if (count($channelIds) === 0) {
            return array();
        }

        $db = $this->getDb();
        array_walk($channelIds, array($db, 'quote'));

        $values = implode(',', $channelIds);

        $query = 'SELECT A.id_news FROM news A, news_canale B
        WHERE A.id_news = B.id_news AND eliminata!='
        . $db->quote(NewsItem::ELIMINATA)
        . 'AND ( data_scadenza IS NULL OR \'' . time()
        . '\' < data_scadenza ) AND B.id_canale IN (' . $values
        . ')
        ORDER BY A.data_inserimento DESC';
        $res = $db->limitQuery($query, $offset, $limit);
        //		var_dump($res);
        //		die();
        if (DB::isError($res))
            $this->throwError('_ERROR_DEFAULT',
                    array('id_utente' => $this->sessionUser->getId(),
                            'msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

        $id_news_list = array();

        while ($res->fetchInto($row)) {
            $id_news_list[] = $row[0];
        }

        $res->free();

        return $id_news_list;
    }

    public function countByChannelId($channelId)
    {
        $db = $this->getDb();

        $query = 'SELECT count(A.id_news) FROM news A, news_canale B
        WHERE A.id_news = B.id_news AND eliminata='.$db->quote(NewsItem::NOT_ELIMINATA).
        'AND ( data_scadenza IS NULL OR \''.time().'\' < data_scadenza ) AND B.id_canale = '.$db->quote($channelId).'';
        $res = $db->getOne($query);
        if (DB::isError($res)) {
            $this->throwError('_ERROR_CRITICAL',array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }

        return $res;

    }
}
