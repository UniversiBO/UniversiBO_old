<?php
namespace Universibo\Bundle\LegacyBundle\Entity;

use \DB;

/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class ContattoDocenteRepository extends DoctrineRepository
{
    /**
     * @param  string          $codDocente
     * @return ContattoDocente
     */
    public function findByCodDocente($codDocente)
    {
        $db = $this->getConnection();

        $query = 'SELECT stato, id_utente_assegnato, ultima_modifica, report FROM docente_contatti WHERE cod_doc = '.$db->quote($codDocente);
        $res = $db->executeQuery($query);

        $rows = $res->rowCount();
        if ($rows == 0) {
            return false;
        }

        false !== ($row = $res->fetch(\PDO::FETCH_NUM));

        return new ContattoDocente($codDocente, $row[0], $row[1], $row[2], $row[3]);
    }

    /**
     *
     * @return boolean|\Universibo\Bundle\LegacyBundle\Entity\ContattoDocente[]
     */
    public function findAll()
    {
        $db = $this->getConnection();

        $query = 'SELECT cod_doc, stato, id_utente_assegnato, ultima_modifica, report FROM docente_contatti ';
        $query.= 'WHERE eliminato = '.$db->quote(ContattoDocente::NOT_ELIMINATO);

        $res = $db->executeQuery($query);

        $rows = $res->rowCount();
        if( $rows == 0) return false;

        $elenco = array();
        while ($row = $res->fetchRow())
            $elenco[] = new ContattoDocente($row[0], $row[1], $row[2], $row[3], $row[4]);

        return $elenco;
    }

    /**
     * @param  ContattoDocente $contattoDocente
     * @return boolean
     */
    public function update(ContattoDocente $contattoDocente)
    {
        $db = $this->getConnection();

        ignore_user_abort(1);
        $db->autoCommit(false);
        $contattoDocente->setUltimaModifica(time());
        $query = 'UPDATE docente_contatti SET stato = '.$db->quote($contattoDocente->getStato())
        .' , id_utente_assegnato = '.$db->quote($contattoDocente->getIdUtenteAssegnato())
        .' , ultima_modifica = '.$db->quote($contattoDocente->getUltimaModifica())
        .' , report = '.$db->quote($contattoDocente->getReport())
        .' WHERE cod_doc = '.$db->quote($contattoDocente->getCodDoc());
        //echo $query;
        $res = $db->executeQuery($query);
        //var_dump($query);

        $this->checkState($contattoDocente);

        $db->commit();
        $db->autoCommit(true);
        ignore_user_abort(0);

        return true;
    }

    /**
     * @param  ContattoDocente $contattoDocente
     * @return boolean
     */
    public function insert(ContattoDocente $contattoDocente)
    {
        $db = $this->getConnection();

        $cod = $this->getCodDoc();
        ignore_user_abort(1);
        $db->autoCommit(false);

        $query = 'SELECT * FROM docente_contatti WHERE cod_doc = '.$db->quote($cod);
        //        echo $query;		die;
        $res = $db->executeQuery($query);
        //var_dump($query);

        $rows = $res->rowCount();
        if( $rows > 0) return false;

        $query = 'INSERT INTO docente_contatti (cod_doc,stato,id_utente_assegnato,ultima_modifica,report) VALUES ' .
                '( ' .$db->quote($contattoDocente->getCodDoc())
                .' , ' .$db->quote($contattoDocente->getStato())
                .' , '.$db->quote($contattoDocente->getIdUtenteAssegnato())
                .' , '.$db->quote($contattoDocente->getUltimaModifica())
                .' , '.$db->quote($contattoDocente->getReport())
                .' )';
        //		echo $query;		die;
        $res = $db->executeQuery($query);
        //var_dump($query);

        $this->checkState($contattoDocente);

        $db->commit();
        $db->autoCommit(true);
        ignore_user_abort(0);

        return true;
    }

    /**
     * @todo è il posto giusto?
     */
    private function checkState(ContattoDocente $contattoDocente)
    {
        $db = $this->getConnection();

        if ($contattoDocente->getStato() != APERTO && $contattoDocente->getStato() != null) {
            $time	= time();
            $query	= 'UPDATE docente SET '
            .' docente_contattato = '.$db->quote($time)
            .' , id_mod = '.$db->quote($contattoDocente->getIdUtenteAssegnato())
            .' WHERE cod_doc = '.$db->quote($contattoDocente->getCodDoc());
            //echo $query;
            $res = $db->executeQuery($query);
            //var_dump($query);
        }
    }
}
