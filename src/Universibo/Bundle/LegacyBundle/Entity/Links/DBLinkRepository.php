<?php
namespace Universibo\Bundle\LegacyBundle\Entity\Links;

use Universibo\Bundle\LegacyBundle\PearDB\DB;
use Universibo\Bundle\CoreBundle\Entity\MergeableRepositoryInterface;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\CoreBundle\Entity\UserRepository;
use Universibo\Bundle\LegacyBundle\Entity\DBRepository;
use Universibo\Bundle\LegacyBundle\PearDB\ConnectionWrapper;

/**
 * Link repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBLinkRepository extends DBRepository implements MergeableRepositoryInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(ConnectionWrapper $db, UserRepository $userRepository, $convert = false)
    {
        parent::__construct($db, $convert);

        $this->userRepository = $userRepository;
    }

    public function findByChannelId($channelId)
    {
        $db = $this->getDb();

        $query = 'SELECT id_link, id_canale, id_utente, uri, label, description FROM link WHERE id_canale = ('
        . $db->quote($channelId) . ') ORDER BY id_link DESC';
        $res = $db->query($query);
        if (DB::isError($res))
            $this->throwError('_ERROR_CRITICAL',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

        $rows = $res->numRows();

        if ($rows = 0)
            return false;
        $link_list = array();

        while ($row = $this->fetchRow($res)) {
            $link_list[] = new Link($row[0], $row[1], $row[2], $row[3],
                    $row[4], $row[5]);
        }

        $res->free();

        return $link_list;
    }

    public function countByUser(User $user)
    {
        $db = $this->getDb();

        $query = <<<EOT
SELECT COUNT(*)
    FROM link l
    WHERE l.id_utente = {$db->quote($user->getId())}
EOT;
        $res = $db->query($query);
        $row = $res->fetchRow();

        return intval($row[0]);
    }

    public function find($id)
    {
        $db = $this->getDb();
        $result = $this->findMany(array($id));

        return is_array($result) ? $result[0] : $result;
    }

    public function findMany(array $ids)
    {
        $db = $this->getDb();

        if (count($ids) == 0) {
            $ret = array();

            return $ret;
        }

        $values = implode(',', $ids);
        $query = 'SELECT id_link, id_canale, uri, label, description, id_utente FROM link WHERE id_link IN ('
        . $values . ')';
        $res = $db->query($query);
        if (DB::isError($res))
            $this->throwError('_ERROR_CRITICAL',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

        $rows = $res->numRows();

        if ($rows == 0) {
            $ret = false;

            return $ret;
        }
        $link_list = array();

        while ($row = $this->fetchRow($res)) {
            $link_list[] = new Link($row[0], $row[1], $row[5], $row[2],
                    $row[3], $row[4]);
        }

        $res->free();

        return $link_list;
    }

    public function insert(Link $link)
    {
        $db = $this->getDb();

        $link->setIdLink($db->nextID('link_id_link'));

        $query = 'INSERT INTO link (id_link, id_canale, id_utente, uri, label, description) VALUES ('
        . $link->getIdLink() . ' , ' . $link->getIdCanale() . ' , '
        . $link->getIdUtente() . ' , ' . $db->quote($link->getUri())
        . ' , ' . $db->quote($link->getLabel()) . ' , '
        . $db->quote($link->getDescription()) . ' )';

        $res = $db->query($query);
        if (DB::isError($res)) {
            $this->throwError('_ERROR_CRITICAL',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

            return false;
        }

        return true;
    }

    public function update(Link $link)
    {
        $db = $this->getDb();

        $query = 'UPDATE link SET uri = ' . $db->quote($link->getUri())
        . ' , label = ' . $db->quote($link->getLabel())
        . ' , id_canale = ' . $link->getIdCanale() . ' , id_utente = '
        . $link->getIdUtente() . ' , description = '
        . $db->quote($link->getDescription()) . ' WHERE id_link = '
        . $link->getIdLink();

        //echo $query;
        $res = $db->query($query);
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
        $db = $this->getDb();

        $query = 'DELETE FROM link WHERE id_link= '
        . $db->quote($link->getIdLink());
        $res = $db->query($query);
        if (DB::isError($res))
            $this->throwError('_ERROR_CRITICAL',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
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

    public function transferOwnership(User $source, User $target)
    {
        $db = $this->getDb();

        $query = <<<EOT
UPDATE link
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
