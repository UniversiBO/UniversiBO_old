<?php
namespace Universibo\Bundle\LegacyBundle\Entity\Commenti;
use Universibo\Bundle\LegacyBundle\Entity\DoctrineRepository;

/**
 * DBCommentoItem repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class CommentoItemRepository extends DoctrineRepository
{
    public function find($id)
    {
        $db = $this->getConnection();

        $query = 'SELECT id_file,id_utente,commento,voto FROM file_studente_commenti WHERE id_commento='
        . $db->quote($id) . ' AND eliminato = '
        . $db->quote(CommentoItem::NOT_ELIMINATO);
        $stmt = $db->executeQuery($query);

        if (false !== ($row = $stmt->fetch())) {
            return new CommentoItem($id, $row[0], $row[1],
                    $row[2], $row[3], CommentoItem::NOT_ELIMINATO);
        }

        return false;
    }

    public function findByFileId($fileId)
    {
        $db = $this->getConnection();

        $query = 'SELECT id_commento,id_utente,commento,voto FROM file_studente_commenti WHERE id_file='
                . $db->quote($fileId) . ' AND eliminato = '
                . $db->quote(CommentoItem::NOT_ELIMINATO)
                . ' ORDER BY voto DESC';
        $stmt = $db->executeQuery($query);

        $commenti_list = array();

        while (false !== ($row = $stmt->fetch())) {
            $commenti_list[] = new CommentoItem($row[0], $fileId, $row[1],
                    $row[2], $row[3], CommentoItem::NOT_ELIMINATO);
        }

        return $commenti_list;
    }

    public function countByFile($fileId)
    {
        $db = $this->getConnection();

        $query = 'SELECT count(*) FROM file_studente_commenti WHERE id_file = '
        . $db->quote($fileId) . ' AND eliminato = '
        . $db->quote(CommentoItem::NOT_ELIMINATO) . ' GROUP BY id_file';
        $stmt = $db->executeQuery($query);

        return $stmt->fetchColumn();
    }

    public function insertFromFields($id_file_studente, $id_utente, $commento, $voto)
    {
        $db = $this->getConnection();

        $query = 'INSERT INTO file_studente_commenti (id_file,id_utente,commento,voto,eliminato) VALUES ('
        . $next_id . ',' . $db->quote($id_file_studente) . ','
        . $db->quote($id_utente) . ',' . $db->quote($commento) . ','
        . $db->quote($voto) . ',' . $db->quote(CommentoItem::NOT_ELIMINATO)
        . ')';
        $res = $db->executeUpdate($query);

        return true;
    }

    public function updateFromFields($id_commento, $commento, $voto)
    {
        $db = $this->getConnection();

        $query = 'UPDATE file_studente_commenti SET commento='
        . $db->quote($commento) . ', voto= ' . $db->quote($voto)
        . ' WHERE id_commento=' . $db->quote($id_commento);
        $res = $db->executeUpdate($query);

        return true;
    }

    public function insert(CommentoItem $comment)
    {
        $db = $this->getConnection();

        $query = 'INSERT INTO file_studente_commenti (id_file,id_utente,commento,voto,eliminato) VALUES ('
        . $next_id . ',' . $db->quote($id_file_studente) . ','
        . $db->quote($id_utente) . ',' . $db->quote($commento) . ','
        . $db->quote($voto) . ',' . $db->quote(CommentoItem::NOT_ELIMINATO)
        . ')';
        $res = $db->executeUpdate($query);
        $id = $db->lastInsertId('file_studente_commenti_id_commento');

        $comment->setIdCommento($id);

        return true;
    }

    public function deleteById($id)
    {
        $db = $this->getConnection();

        $query = 'UPDATE file_studente_commenti SET eliminato = '
        . $db->quote(CommentoItem::ELIMINATO) . 'WHERE id_commento='
        . $db->quote($id);
        $res = $db->executeUpdate($query);

        return true;
    }

    public function exists($id_file, $id_utente)
    {
        $db = $this->getConnection();

        $query = 'SELECT id_commento FROM file_studente_commenti WHERE id_file ='
        . $db->quote($id_file) . ' AND id_utente = '
        . $db->quote($id_utente) . ' AND eliminato = '
        . $db->quote(CommentoItem::NOT_ELIMINATO)
        . 'GROUP BY id_file,id_utente,id_commento';
        $stmt = $db->executeQuery($query);

        return $stmt->fetchColumn();
    }
}
