<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity\News;

use \DB;
use \Error;
use UniversiBO\Bundle\LegacyBundle\Entity\DBRepository;
use UniversiBO\Bundle\LegacyBundle\Entity\DBUserRepository;

/**
 * DBNewsItem repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBNewsItemRepository extends DBRepository
{
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
            $userRepository = new DBUserRepository($db);
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
            $userRepository = new DBUserRepository($db);
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
            $userRepository = new DBUserRepository($db);

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
}
