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

        $query = 'SELECT stato, id_utente_assegnato, ultima_modifica, report FROM docente_contatti WHERE cod_doc = ?';
        $stmt = $db->executeQuery($query, array($codDocente));
        
        $row = $stmt->fetch();
        
        if($row === false) {
            return false;
        }
        
        return new ContattoDocente($codDocente, $row[0], $row[1], $row[2], $row[3]);
    }

    /**
     *
     * @return boolean|\Universibo\Bundle\LegacyBundle\Entity\ContattoDocente[]
     */
    public function findAll()
    {
        $db = $this->getConnection();

        $query = 'SELECT cod_doc, stato, id_utente_assegnato, ultima_modifica, report FROM docente_contatti WHERE eliminato = ?';
        $stmt = $db->executeQuery($query, array(ContattoDocente::NOT_ELIMINATO));
        
        if($stmt->rowCount() === 0) {
            return false;
        }

        $elenco = array();
        while (false !== ($row = $stmt->fetch())) {
            $elenco[] = new ContattoDocente($row[0], $row[1], $row[2], $row[3], $row[4]);
        }

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
        
        $db->executeUpdate($query, array(
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

        ignore_user_abort(1);
        $db->beginTransaction();
        
        if($this->exists($contattoDocente->getCodDoc())) {
            $db->rollback();
            return false;
        }
        
        $query = 'INSERT INTO docente_contatti (cod_doc,stato,id_utente_assegnato,ultima_modifica,report) VALUES (?,?,?,?,?)';
        $db->executeUpdate($query, array(
                $contattoDocente->getCodDoc(),
                $contattoDocente->getIdUtenteAssegnato(),
                $contattoDocente->getUltimaModifica(),
                $contattoDocente->getReport()
        ));

        $this->checkState($contattoDocente);

        $db->commit();
        ignore_user_abort(0);

        return true;
    }
    
    public function exists($codDoc)
    {
        $query = 'SELECT COUNT(*) FROM docente_contatti WHERE cod_doc = ?';
        $stmt = $this->getConnection()->executeQuery($query, array($codDoc));
        
        return $stmt->fetchColumn() > 0;
    }

    /**
     * @todo è il posto giusto? BOH
     */
    private function checkState(ContattoDocente $contattoDocente)
    {
        $db = $this->getConnection();

        if ($contattoDocente->getStato() !== ContattoDocente::APERTO && $contattoDocente->getStato() !== null) {
            $time	= time();
            $query	= 'UPDATE docente SET '
            .' docente_contattato = ?'
            .' , id_mod = ?'
            .' WHERE cod_doc = ?';
            
            $db->executeUpdate($query, array(time(), $contattoDocente->getIdUtenteAssegnato(), $contattoDocente->getCodDoc()));
        }
    }
}
