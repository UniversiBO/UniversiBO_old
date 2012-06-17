<?php
namespace Universibo\Bundle\LegacyBundle\Entity;

use \DB;

/**
 * Ruolo repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class RuoloRepository extends DoctrineRepository
{
    public function delete(Ruolo $ruolo)
    {
        $db = $this->getConnection();
        $query = 'DELETE FROM utente_canale WHERE id_utente = ? AND id_canale = ?';

        return $db->executeUpdate($query, array($ruolo->getIdUser(), $ruolo->getIdCanale())) > 0;
    }

    public function insert(Ruolo $ruolo)
    {
        $db = $this->getConnection();

        $campo_ruolo = ($ruolo->isModeratore()) ? Ruolo::MODERATORE : 0 + ($ruolo->isReferente()) ? Ruolo::REFERENTE : 0;
        $my_universibo = ($ruolo->isMyUniversibo()) ? 'S' : 'N';
        $nascosto = ($ruolo->isNascosto()) ? 'S' : 'N';

        $query = 'INSERT INTO utente_canale(id_utente, id_canale, ultimo_accesso, ruolo, my_universibo, notifica, nome, nascosto) VALUES (?,?,?,?,?,?,?,?)';

        $db->executeUpdate($query, array(
                $ruolo->getIdUser(),
                $ruolo->getIdCanale(),
                $ruolo->getUltimoAccesso(),
                $campo_ruolo,
                $my_universivo,
                $ruolo->getTipoNotifica(),
                $ruolo->getNome(),
                $nascosto
        ));

        return true;
    }

    public function updateNome(Ruolo $ruolo)
    {
        $db = $this->getConnection();
        $query = 'UPDATE utente_canale SET nome = ? WHERE id_utente = ? AND id_canale = ?';

        return $db->executeUpdate($query, array($ruolo->getNome(), $ruolo->getIdUser(), $ruolo->getIdCanale())) > 0;
    }

    public function updateUltimoAccesso(Ruolo $ruolo)
    {
        $db = $this->getConnection();
        $query = 'UPDATE utente_canale SET ultimo_accesso = ? WHERE id_utente = ? AND id_canale = ?';

        return $db->executeUpdate($query, array($ruolo->getUltimoAccesso(), $ruolo->getIdUser(), $ruolo->getIdCanale())) > 0;
    }

    public function updateTipoNotifica(Ruolo $ruolo)
    {
        $db = $this->getConnection();
        $query = 'UPDATE utente_canale SET tipo_notifica = ? WHERE id_utente = ? AND id_canale = ?';

        return $db->executeUpdate($query, array($ruolo->getTipoNotifica(), $ruolo->getIdUser(), $ruolo->getIdCanale())) > 0;
    }

    public function updateModeratore(Ruolo $ruolo)
    {
        $campo_ruolo = ($ruolo->isModeratore()) ? Ruolo::MODERATORE : 0 + ($ruolo->isReferente()) ? Ruolo::REFERENTE : 0;

        $db = $this->getConnection();
        $query = 'UPDATE utente_canale SET ruolo = ? WHERE id_utente = ? AND id_canale = ?';

        return $db->executeUpdate($query, array($campo_ruolo, $ruolo->getIdUser(), $ruolo->getIdCanale())) > 0;
    }

    public function updateReferente(Ruolo $ruolo)
    {
        return $this->updateModeratore($ruolo);
    }

    public function updateMyUniversibo(Ruolo $ruolo)
    {
        $my_universibo = ($ruolo->isMyUniversibo()) ? 'S' : 'N';

        $db = $this->getConnection();
        $query = 'UPDATE utente_canale SET my_universibo = ? WHERE id_utente = ? AND id_canale = ?';

        return $db->executeUpdate($query, array($my_universibo, $ruolo->getIdUser(), $ruolo->getIdCanale())) > 0;
    }

    public function findByIdCanale($idCanale)
    {
        $db = $this->getConnection();

        $query = 'SELECT id_utente, ultimo_accesso, ruolo, my_universibo, notifica, nome, nascosto FROM utente_canale WHERE id_canale = ?';
        $stmt = $db->executeQuery($query, array($idCanale));

        $ruoli = array();
        while (false !== ($row = $stmt->fetch())) {
            $ruoli[] = new Ruolo($row[0], $idCanale, $row[5], $row[1], $row[2]==Ruolo::MODERATORE, $row[2]==Ruolo::REFERENTE, $row[3]=='S', $row[4], $row[6]=='S');
        }

        return $ruoli;
    }

    public function update(Ruolo $ruolo)
    {
        $db = $this->getConnection();

        $campo_ruolo = (($ruolo->isModeratore()) ? Ruolo::MODERATORE : 0) + (($ruolo->isReferente()) ? Ruolo::REFERENTE : 0);
        $my_universibo = ($ruolo->isMyUniversibo()) ? 'S' : 'N';
        $nascosto = ($ruolo->isNascosto()) ? 'S' : 'N';

        $query = 'UPDATE utente_canale SET ultimo_accesso = ?'.
        ', ruolo = ?'.
        ', my_universibo = ?'.
        ', notifica = ?'.
        ', nome = ?'.
        ', nascosto = ?'.
        ' WHERE id_utente = ?'.
        ' AND id_canale = ?';

        return $db->executeUpdate($query, array(
                $ruolo->getUltimoAccesso(),
                $campo_ruolo,
                $my_universibo,
                $ruolo->getTipoNotifica(),
                $ruolo->getNome(),
                $nascosto,
                $ruolo->getIdUser(),
                $ruolo->getIdCanale()
        )) > 0;
    }

    public function find($idUtente, $idCanale)
    {
        $db = $this->getConnection();

        $query = 'SELECT ultimo_accesso, ruolo, my_universibo, notifica, nome, nascosto FROM utente_canale WHERE id_utente = ? AND id_canale= ?';
        $stmt = $db->executeQuery($query, array($idUtente, $idCanale));

        $row = $stmt->fetch();

        if ($row === false) {
            return false;
        }

        return new Ruolo($idUtente, $idCanale, $row[4], $row[0], $row[1]==RUOLO_MODERATORE, $row[1]==Ruolo::REFERENTE, $row[2]=='S', $row[3], $row[5]=='S');
    }

    public function exists($idUtente, $idCanale)
    {
        $db = $this->getConnection();

        $query = 'SELECT id_utente, id_canale FROM utente_canale WHERE id_utente = ? AND id_canale = ?';
        $stmt = $db->executeQuery($query, array($idUtente, $idCanale));

        return $stmt->rowCount() > 0;
    }

    public function findByIdUtente($idUtente)
    {
        $db = $this->getConnection();

        $query = 'SELECT id_canale, ultimo_accesso, ruolo, my_universibo, notifica, nome, nascosto FROM utente_canale WHERE id_utente = ?';
        $stmt = $db->executeQuery($query, array($idUtente));

        $ruoli = array();
        while (false !== ($row = $stmt->fetch())) {
            $ruoli[] = new Ruolo($idUtente, $row[0], $row[5], $row[1], $row[2]==Ruolo::MODERATORE, $row[2]==Ruolo::REFERENTE, $row[3]=='S', $row[4], $row[6]=='S');
        }

        return $ruoli;
    }
}
