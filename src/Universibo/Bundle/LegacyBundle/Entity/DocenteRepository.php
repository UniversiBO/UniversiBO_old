<?php
namespace Universibo\Bundle\LegacyBundle\Entity;
use \DB;

/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DocenteRepository extends DoctrineRepository
{
    public function find($id)
    {
        return $this->findBy('cod_doc', $id);
    }

    public function findByUserId($id)
    {
        return $this->findBy('id_utente', $id);
    }

    public function findBy($field, $id)
    {
        $db = $this->getConnection();
        
        $query = 'SELECT id_utente,	cod_doc, nome_doc FROM docente WHERE '. $field . ' = ?';
        $stmt = $db->executeQuery($query, array($id));
        
        $row = $stmt->fetch();
        
        if($row === false) {
        	return false;
        }
        
        return new Docente($row[0], $row[1], $row[2]);
    }

    public function getInfo(Docente $docente)
    {
        $db = $this->getConnection();

        $query = 'SELECT nome, cognome, prefissonome, sesso, email, descrizionestruttura FROM rub_docente WHERE cod_doc = ?';
        $stmt = $db->executeQuery($query, array($docente->getCodDoc()));
        
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if($row === false) {
            return false;
        }

        return array_combine(array('nome', 'cognome', 'prefissonome', 'sesso', 'email','descrizionestruttura'), $row);
    }
}
