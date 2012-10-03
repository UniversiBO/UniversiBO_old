<?php
namespace Universibo\Bundle\LegacyBundle\Entity;

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
        while (false !== ($row = $res->fetch(\PDO::FETCH_NUM)))
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
        $db->beginTransaction();
        $contattoDocente->setUltimaModifica(time());
        $query = 'UPDATE docente_contatti SET stato = ?'
        .' , id_utente_assegnato = ?'
        .' , ultima_modifica = ?'
        .' , report = ?'
        .' WHERE cod_doc = ?';
        //echo $query;
        $res = $db->executeQuery($query, array (
                $contattoDocente->getStato(),
                $contattoDocente->getIdUtenteAssegnato(),
                $contattoDocente->getUltimaModifica(),
                $contattoDocente->getReport(),
                $contattoDocente->getCodDoc()
        ));

        $this->checkState($contattoDocente);

        $db->commit();
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
        $db->beginTransaction();

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
        ignore_user_abort(0);

        return true;
    }

    /**
     * @todo è il posto giusto?
     */
    private function checkState(ContattoDocente $contattoDocente)
    {
        $db = $this->getConnection();

        if ($contattoDocente->getStato() != ContattoDocente::APERTO && $contattoDocente->getStato() != null) {
            $time	= time();
            $query	= 'UPDATE docente SET '
            .' docente_contattato = ?'
            .' , id_mod = ?'
            .' WHERE cod_doc = ?';

            $res = $db->executeQuery($query, array(
                    time(),
                    $contattoDocente->getIdUtenteAssegnato(),
                    $contattoDocente->getCodDoc()
            ));
        }
    }
}
