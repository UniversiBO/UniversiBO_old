<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity\Commenti;
use \DB;
use UniversiBO\Bundle\LegacyBundle\Entity\DBRepository;

/**
 * DBCommentoItem repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBCommentoItemRepository extends DBRepository
{
    public function find($id)
    {
        $db = $this->getDb();

        $query = 'SELECT id_file,id_utente,commento,voto FROM file_studente_commenti WHERE id_commento='
        . $db->quote($id_commento) . ' AND eliminato = '
        . $db->quote(self::NOT_ELIMINATO);
        $res = $db->query($query);

        if (DB::isError($res))
            Error::throwError(_ERROR_DEFAULT,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

        if ($res->fetchInto($row)) {
            $commenti = new CommentoItem($id_commento, $row[0], $row[1],
                    $row[2], $row[3], self::NOT_ELIMINATO);
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
            Error::throwError(_ERROR_DEFAULT,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
        }

        $res->fetchInto($row);
        $res->free();

        return $row[0];
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
        . $db->quote($voto) . ',' . $db->quote(self::NOT_ELIMINATO)
        . ')';
        $res = $db->query($query);
        if (DB::isError($res)) {
            $db->rollback();
            Error::throwError(_ERROR_DEFAULT,
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
            Error::throwError(_ERROR_DEFAULT,
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
            $return = false;
        }
    }

    public function exists($userId, $fileId)
    {
        $db = $this->getDb();
    }
}
