<?php
namespace Universibo\Bundle\LegacyBundle\Entity\Links;
use Doctrine\DBAL\Driver\Connection;

use Universibo\Bundle\CoreBundle\Entity\UserRepository;

use \DB;
use Universibo\Bundle\LegacyBundle\Entity\DoctrineRepository;

/**
 * Link repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class LinkRepository extends DoctrineRepository
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(Connection $db, UserRepository $userRepository, $convert = false)
    {
        parent::__construct($db);

        $this->userRepository = $userRepository;
    }

    public function findByChannelId($channelId)
    {
        $db = $this->getConnection();

        $query = 'SELECT id_link, id_canale, id_utente, uri, label, description FROM link WHERE id_canale = ('
        . $db->quote($channelId) . ') ORDER BY id_link DESC';
        $res = $db->executeQuery($query);

        $rows = $res->rowCount();

        if ($rows = 0)
            return false;
        $link_list = array();

        while (false !== ($row = $res->fetch(\PDO::FETCH_NUM))) {
            $link_list[] = new Link($row[0], $row[1], $row[2], $row[3],
                    $row[4], $row[5]);
        }

        return $link_list;
    }

    public function find($id)
    {
        $db = $this->getConnection();
        $result = $this->findMany(array($id));

        return is_array($result) ? $result[0] : $result;
    }

    public function findMany(array $ids)
    {
        $db = $this->getConnection();

        if (count($ids) == 0) {
            $ret = array();

            return $ret;
        }

        $values = implode(',', $ids);
        $query = 'SELECT id_link, id_canale, uri, label, description, id_utente FROM link WHERE id_link IN ('
        . $values . ')';
        $res = $db->executeQuery($query);

        $rows = $res->rowCount();

        if ($rows == 0) {
            $ret = false;

            return $ret;
        }
        $link_list = array();

        while (false !== ($row = $res->fetch())) {
            $link_list[] = new Link($row[0], $row[1], $row[5], $row[2],
                    $row[3], $row[4]);
        }

        $res->free();

        return $link_list;
    }

    public function insert(Link $link)
    {
        $db = $this->getConnection();

        $link->setIdLink($db->nextID('link_id_link'));

        $query = 'INSERT INTO link (id_link, id_canale, id_utente, uri, label, description) VALUES ('
        . $link->getIdLink() . ' , ' . $link->getIdCanale() . ' , '
        . $link->getIdUtente() . ' , ' . $db->quote($link->getUri())
        . ' , ' . $db->quote($link->getLabel()) . ' , '
        . $db->quote($link->getDescription()) . ' )';

        $db->executeUpdate($query);

        return true;
    }

    public function update(Link $link)
    {
        $db = $this->getConnection();

        $query = 'UPDATE link SET uri = ' . $db->quote($link->getUri())
        . ' , label = ' . $db->quote($link->getLabel())
        . ' , id_canale = ' . $link->getIdCanale() . ' , id_utente = '
        . $link->getIdUtente() . ' , description = '
        . $db->quote($link->getDescription()) . ' WHERE id_link = '
        . $link->getIdLink();

        //echo $query;
        $res = $db->executeQuery($query);
        if (DB::isError($res))
            $this->throwError('_ERROR_DEFAULT',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
        $rows = $db->affectedRows();

        if ($rows == 1)
            return true;
        elseif ($rows == 0)
        return false;
        else
            $this->throwError('_ERROR_DEFAULT',
                    array('msg' => 'Errore generale database: canale non unico',
                            'file' => __FILE__, 'line' => __LINE__));
    }

    public function delete(Link $link)
    {

        $query = 'DELETE FROM link WHERE id_link= '
        . $db->quote($link->getIdLink());
        $res = $db->executeQuery($query);

        $rows = $db->affectedRows();

        if ($rows == 1)
            return true;
        elseif ($rows == 0)
        return false;
        else
            $this->throwError('_ERROR_CRITICAL',
                    array('msg' => 'Errore generale database: canale non unico',
                            'file' => __FILE__, 'line' => __LINE__));
    }

    public function getUsername(Link $link)
    {
        return $this->userRepository->getUsernameFromId($link->getIdUtente());
    }
}
