<?php
namespace Universibo\Bundle\LegacyBundle\Entity\News;

use Doctrine\DBAL\Connection;

use Universibo\Bundle\LegacyBundle\Entity\DoctrineRepository;
use Universibo\Bundle\LegacyBundle\Entity\CanaleRepository;
use Universibo\Bundle\LegacyBundle\Entity\UserRepository;

/**
 * DBNewsItem repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class NewsItemRepository extends DoctrineRepository
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var CanaleRepository
     */
    private $channelRepository;

    public function __construct(Connection $db, UserRepository $userRepository, CanaleRepository $channelRepository)
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

    public function findAll()
    {
        $db = $this->getConnection();

        //		$query = 'SELECT titolo, notizia, data_inserimento, data_scadenza, flag_urgente, eliminata, A.id_utente, id_news, username, data_modifica FROM news A, utente B WHERE A.id_utente = B.id_utente AND id_news IN ('.$values.') AND eliminata!='.$db->quote(NewsItem::ELIMINATA);
        $query = 'SELECT titolo, notizia, data_inserimento, data_scadenza, flag_urgente, eliminata, A.id_utente, id_news, data_modifica FROM news A WHERE eliminata='
                . $db->quote(NewsItem::NOT_ELIMINATA)
                . ' ORDER BY data_inserimento DESC';
        //var_dump($query);
        $stmt = $db->executeQuery($query);
        
        if($stmt->rowCount() === 0) {
            return false;
        }

        $news_list = array();

        $userRepository = $this->userRepository;
        
        while (false !== ($row = $stmt->fetch())) {
            $username = $userRepository->getUsernameFromId($row[6]);

            $news_list[] = new NewsItem($row[7], $row[0], $row[1], $row[2],
                    $row[3], $row[8], ($row[4] == NewsItem::URGENTE),
                    ($row[5] == NewsItem::ELIMINATA), $row[6], $username);
        }

        return $news_list;
    }

    public function findMany(array $ids)
    {
        if (count($ids) === 0) {
            return array();
        }

        $db = $this->getConnection();

        array_walk($ids, 'intval');
        $values = implode(',', $ids);

        //		$query = 'SELECT titolo, notizia, data_inserimento, data_scadenza, flag_urgente, eliminata, A.id_utente, id_news, username, data_modifica FROM news A, utente B WHERE A.id_utente = B.id_utente AND id_news IN ('.$values.') AND eliminata!='.$db->quote(NewsItem::ELIMINATA);
        $query = 'SELECT titolo, notizia, data_inserimento, data_scadenza, flag_urgente, eliminata, A.id_utente, id_news, data_modifica FROM news A WHERE id_news IN ('
                . $values . ') AND eliminata='
                . $db->quote(NewsItem::NOT_ELIMINATA)
                . ' ORDER BY data_inserimento DESC';
        //var_dump($query);
        $stmt = $db->executeQuery($query);

        if($stmt->rowCount() === 0) {
            return false;
        }
        
        $news_list = array();

        while (false !== ($row = $stmt->fetch())) {
            $userRepository = $this->userRepository;
            $username = $userRepository->getUsernameFromId($row[6]);

            $news_list[] = new NewsItem($row[7], $row[0], $row[1], $row[2],
                    $row[3], $row[8], ($row[4] == NewsItem::URGENTE),
                    ($row[5] == NewsItem::ELIMINATA), $row[6], $username);
        }

        return $news_list;
    }

    public function findByCanale($id, $limit = null, $expired = false)
    {
        $db = $this->getConnection();

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

        $stmt = $db->executeQuery($sql);

        $news = array();
        while (false !== ($row = $stmt->fetch())) {
            $userRepository = $this->userRepository;

            $news[] = new NewsItem($row[7], $row[0], $row[1], $row[2], $row[3],
                    $row[8], ($row[4] == NewsItem::URGENTE),
                    ($row[5] == NewsItem::ELIMINATA), $row[9], $row[6]);
        }

        return $news;
    }

    public function insert(NewsItem $newsItem)
    {
        $db = $this->getConnection();

        ignore_user_abort(1);
        $db->beginTransaction();
        
        $scadenza = ($newsItem->getDataScadenza() == NULL) ? ' NULL '
                : $db->quote($newsItem->getDataScadenza());
        $eliminata = ($newsItem->isEliminata()) ? NewsItem::ELIMINATA
                : NewsItem::NOT_ELIMINATA;
        $flag_urgente = ($newsItem->isUrgente()) ? NewsItem::URGENTE
                : NewsItem::NOT_URGENTE;
        $query = 'INSERT INTO news (titolo, data_inserimento, data_scadenza, notizia, id_utente, eliminata, flag_urgente, data_modifica) VALUES '
                . '( ' . $db->quote($newsItem->getTitolo())
                . ' , ' . $db->quote($newsItem->getDataIns()) . ' , '
                . $scadenza . ' , ' . $db->quote($newsItem->getNotizia())
                . ' , ' . $db->quote($newsItem->getIdUtente()) . ' , '
                . $db->quote($eliminata) . ' , ' . $db->quote($flag_urgente)
                . ' , ' . $db->quote($newsItem->getUltimaModifica()) . ' )';
        $res = $db->executeUpdate($query);

        $newsItem->setIdNotizia($db->lastInsertId('news_id_news_seq'));

        $db->commit();
        ignore_user_abort(0);

        return true;
    }

    public function getChannelIdList(NewsItem $news)
    {
        $db = $this->getConnection();

        $query = 'SELECT id_canale FROM news_canale WHERE id_news='.$db->quote($news->getIdNotizia()).' ORDER BY id_canale';
        $stmt = $db->executeQuery($query);

        $elenco_id_canale = array();

        while (false !== ($id = $stmt->fetchColumn())) {
            $elenco_id_canale[] = $id;
        }

        return $elenco_id_canale;
    }

    public function removeFromChannel(NewsItem $news, $channelId)
    {
        $db = $this->getConnection();

        $query = 'DELETE FROM news_canale WHERE id_canale='.$db->quote($channelId).' AND id_news='.$db->quote($news->getIdNotizia());
        $res = $db->executeUpdate($query);

        $news->setIdCanali($ids = array_diff ($news->getIdCanali(), array($id_canale)));

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

        $db = $this->getConnection();
        $query = 'INSERT INTO news_canale (id_news, id_canale) VALUES ('.$db->quote($news->getIdNotizia()).','.$db->quote($channelId).')';

        $db->executeUpdate($query);

        $ids = $news->getIdCanali();
        $ids[] = $channelId;
        $news->setIdCanali($ids);

        return true;
    }

    public function update(NewsItem $news)
    {
        $db = $this->getConnection();

        $db->beginTransaction();
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
        $res = $db->executeUpdate($query);

        $db->commit();
        
        return true;
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

        $db = $this->getConnection();
        $builder = $db->createQueryBuilder();
        
        $stmt = $builder
            ->select('n.id_news')
            ->from('news', 'n')
            ->from('news_canale', 'nc')
            ->where('n.id_news = nc.id_news')
            ->andWhere('n.eliminata = ?')
            ->andWhere('nc.id_canale IN (?)')
            ->andWhere('n.data_scadenza IS NULL OR n.data_scadenza > ?')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->setParameters(array(NewsItem::NOT_ELIMINATA, $channelIds, time()), array(null, Connection::PARAM_INT_ARRAY, null))
            ->execute()
        ;
        $id_news_list = array();

        while (false !== ($id = $stmt->fetchColumn())) {
            $id_news_list[] = $id;
        }

        return $id_news_list;
    }

    public function countByChannelId($channelId)
    {
        $db = $this->getConnection();

        $query = 'SELECT count(A.id_news) FROM news A, news_canale B
        WHERE A.id_news = B.id_news AND eliminata='.$db->quote(NewsItem::NOT_ELIMINATA).
        'AND ( data_scadenza IS NULL OR \''.time().'\' < data_scadenza ) AND B.id_canale = '.$db->quote($channelId).'';
        
        $stmt = $db->executeQuery($query);
        
        return $stmt->fetchColumn();

    }
}
