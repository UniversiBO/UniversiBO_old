<?php
namespace Universibo\Bundle\LegacyBundle\Entity;

use Universibo\Bundle\LegacyBundle\PearDB\DB;
use Universibo\Bundle\LegacyBundle\Framework\Error;
use Universibo\Bundle\LegacyBundle\Entity\ContattoDocente;

/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBContattoDocenteRepository extends DBRepository
{
    /**
     * @deprecated
     * @param  string          $codDocente
     * @return ContattoDocente
     */
    public function findByCodDocente($codDocente)
    {
        return $this->findOneByCodDoc($codDocente);
    }

    public function find($id)
    {
        return $this->findOneByCodDoc($id);
    }

    /**
     * @param  string          $codDocente
     * @return ContattoDocente
     */
    public function findOneByCodDoc($codDocente)
    {
        $db = $this->getDb();

        $query = 'SELECT stato, id_utente_assegnato, ultima_modifica, report FROM docente_contatti WHERE cod_doc = '.$db->quote($codDocente);
        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

        $rows = $res->numRows();
        if ($rows == 0) {
            return false;
        }

        $row = $this->fetchRow($res);

        return new ContattoDocente($codDocente, $row[0], $row[1], $row[2], $row[3]);
    }

    /**
     *
     * @return boolean|ContattoDocente[]
     */
    public function findAll()
    {
        $db = $this->getDb();

        $query = 'SELECT cod_doc, stato, id_utente_assegnato, ultima_modifica, report FROM docente_contatti ';
        $query.= 'WHERE eliminato = '.$db->quote(ContattoDocente::NOT_ELIMINATO);

        $res = $db->query($query);
        if (DB::isError($res))
            Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));

        $rows = $res->numRows();
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
        $db = $this->getDb();

        ignore_user_abort(1);
        $db->autoCommit(false);
        $contattoDocente->setUltimaModifica(time());
        $query = 'UPDATE docente_contatti SET stato = '.$db->quote($contattoDocente->getStato())
        .' , id_utente_assegnato = '.$db->quote($contattoDocente->getIdUtenteAssegnato())
        .' , ultima_modifica = '.$db->quote($contattoDocente->getUltimaModifica())
        .' , report = '.$db->quote($contattoDocente->getReport())
        .' WHERE cod_doc = '.$db->quote($contattoDocente->getCodDoc());
        //echo $query;
        $res = $db->query($query);
        //var_dump($query);
        if (DB::isError($res)) {
            $db->rollback();
            Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }

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
        $db = $this->getDb();

        $cod = $contattoDocente->getCodDoc();
        ignore_user_abort(1);
        $db->autoCommit(false);

        $query = 'SELECT * FROM docente_contatti WHERE cod_doc = '.$db->quote($cod);
        //        echo $query;		die;
        $res = $db->query($query);
        //var_dump($query);
        if (DB::isError($res)) {
            $db->rollback();
            Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }

        $rows = $res->numRows();
        if( $rows > 0) return false;
        $res->free();

        $query = 'INSERT INTO docente_contatti (cod_doc,stato,id_utente_assegnato,ultima_modifica,report) VALUES ' .
                '( ' .$db->quote($contattoDocente->getCodDoc())
                .' , ' .$db->quote($contattoDocente->getStato())
                .' , '.$db->quote($contattoDocente->getIdUtenteAssegnato())
                .' , '.$db->quote($contattoDocente->getUltimaModifica())
                .' , '.$db->quote($contattoDocente->getReport())
                .' )';
        //		echo $query;		die;
        $res = $db->query($query);
        //var_dump($query);
        if (DB::isError($res)) {
            $db->rollback();
            Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }

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
        $db = $this->getDb();

        if ($contattoDocente->getStato() != APERTO && $contattoDocente->getStato() != null) {
            $time	= time();
            $query	= 'UPDATE docente SET '
            .' docente_contattato = '.$db->quote($time)
            .' , id_mod = '.$db->quote($contattoDocente->getIdUtenteAssegnato())
            .' WHERE cod_doc = '.$db->quote($contattoDocente->getCodDoc());
            //echo $query;
            $res = $db->query($query);
            //var_dump($query);
            if (DB::isError($res)) {
                $db->rollback();
                Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
            }
        }
    }
}
