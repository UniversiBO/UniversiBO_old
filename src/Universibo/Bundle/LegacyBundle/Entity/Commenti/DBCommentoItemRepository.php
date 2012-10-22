<?php
namespace Universibo\Bundle\LegacyBundle\Entity\Commenti;

use DB;
use Universibo\Bundle\CoreBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\Entity\DBRepository;
use Universibo\Bundle\LegacyBundle\Entity\MergeableRepositoryInterface;

/**
 * DBCommentoItem repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBCommentoItemRepository extends DBRepository implements MergeableRepositoryInterface
{
    public function countByUser(User $user)
    {
        $db = $this->getDb();

        $query = <<<EOT
SELECT COUNT(*)
    FROM file_studente_commenti f
    WHERE f.id_utente = {$db->quote($user->getId())}
EOT;
        $res = $db->query($query);
        $row = $res->fetchRow();

        return intval($row[0]);
    }

    public function find($id)
    {
        $db = $this->getDb();

        $query = 'SELECT id_file,id_utente,commento,voto FROM file_studente_commenti WHERE id_commento='
        . $db->quote($id) . ' AND eliminato = '
        . $db->quote(CommentoItem::NOT_ELIMINATO);
        $res = $db->query($query);

        if (DB::isError($res))
            $this->throwError('_ERROR_DEFAULT',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

        if ($res->fetchInto($row)) {
            $commenti = new CommentoItem($id, $row[0], $row[1],
                    $row[2], $row[3], CommentoItem::NOT_ELIMINATO);
        } else

            return false;

        $res->free();

        return $commenti;
    }

    public function findByFileId($fileId)
    {
        $db = $this->getDb();

        $query = 'SELECT id_commento,id_utente,commento,voto FROM file_studente_commenti WHERE id_file='
                . $db->quote($fileId) . ' AND eliminato = '
                . $db->quote(CommentoItem::NOT_ELIMINATO)
                . ' ORDER BY voto DESC';
        $res = $db->query($query);

        if (DB::isError($res))
            $this
                    ->throwError('_ERROR_DEFAULT',
                            array('msg' => DB::errorMessage($res),
                                    'file' => __FILE__, 'line' => __LINE__));

        $commenti_list = array();

        while ($res->fetchInto($row)) {
            $commenti_list[] = new CommentoItem($row[0], $fileId, $row[1],
                    $row[2], $row[3], CommentoItem::NOT_ELIMINATO);
        }

        $res->free();

        return $commenti_list;
    }

    public function countByFile($fileId)
    {
        $db = $this->getDb();

        $query = 'SELECT count(*) FROM file_studente_commenti WHERE id_file = '
        . $db->quote($fileId) . ' AND eliminato = '
        . $db->quote(CommentoItem::NOT_ELIMINATO) . ' GROUP BY id_file';
        $res = $db->query($query);

        if (DB::isError($res)) {
            $this->throwError('_ERROR_DEFAULT',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
        }

        $res->fetchInto($row);
        $res->free();

        return $row[0];
    }

    public function insertFromFields($id_file_studente, $id_utente, $commento, $voto)
    {
        $db = $this->getDb();

        $next_id = $db->nextID('file_studente_commenti_id_commento');
        $return = true;
        $query = 'INSERT INTO file_studente_commenti (id_commento,id_file,id_utente,commento,voto,eliminato) VALUES ('
        . $next_id . ',' . $db->quote($id_file_studente) . ','
        . $db->quote($id_utente) . ',' . $db->quote($commento) . ','
        . $db->quote($voto) . ',' . $db->quote(CommentoItem::NOT_ELIMINATO)
        . ')';
        $res = $db->query($query);
        if (DB::isError($res)) {
            $db->rollback();
            $this->throwError('_ERROR_DEFAULT',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
            $return = false;
        }

        return $return;
    }

    public function updateFromFields($id_commento, $commento, $voto)
    {
        $db = $this->getDb();

        $return = true;
        $query = 'UPDATE file_studente_commenti SET commento='
        . $db->quote($commento) . ', voto= ' . $db->quote($voto)
        . ' WHERE id_commento=' . $db->quote($id_commento);
        $res = $db->query($query);
        if (DB::isError($res)) {
            $db->rollback();
            $this->throwError('_ERROR_DEFAULT',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
            $return = false;
        }
    }

    public function insert(CommentoItem $comment)
    {
        $db = $this->getDb();

        $next_id = $db->nextID('file_studente_commenti_id_commento');
        $this->id_commento = $next_id;
        $return = true;
        $query = 'INSERT INTO file_studente_commenti (id_commento,id_file,id_utente,commento,voto,eliminato) VALUES ('
        . $next_id . ',' . $db->quote($id_file_studente) . ','
        . $db->quote($id_utente) . ',' . $db->quote($commento) . ','
        . $db->quote($voto) . ',' . $db->quote(CommentoItem::NOT_ELIMINATO)
        . ')';
        $res = $db->query($query);
        if (DB::isError($res)) {
            $db->rollback();
            $this->throwError('_ERROR_DEFAULT',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
            $return = false;
        }

        return $return;
    }

    public function deleteById($id)
    {
        $db = $this->getDb();

        $return = true;
        $query = 'UPDATE file_studente_commenti SET eliminato = '
        . $db->quote(CommentoItem::ELIMINATO) . 'WHERE id_commento='
        . $db->quote($id);
        $res = $db->query($query);
        if (DB::isError($res)) {
            $db->rollback();
            $this->throwError('_ERROR_DEFAULT',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
            $return = false;
        }
    }

    public function exists($id_file, $id_utente)
    {
        $db = $this->getDb();

        $flag = false;

        $query = 'SELECT id_commento FROM file_studente_commenti WHERE id_file ='
        . $db->quote($id_file) . ' AND id_utente = '
        . $db->quote($id_utente) . ' AND eliminato = '
        . $db->quote(CommentoItem::NOT_ELIMINATO)
        . 'GROUP BY id_file,id_utente,id_commento';
        $res = $db->query($query);

        if (DB::isError($res))
            $this->throwError('_ERROR_DEFAULT',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
        $res->fetchInto($ris);

        return $ris[0];
    }

    public function transferOwnership(User $source, User $target)
    {
    }
}
