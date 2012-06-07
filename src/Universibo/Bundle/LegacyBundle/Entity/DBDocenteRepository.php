<?php
namespace Universibo\Bundle\LegacyBundle\Entity;
use \DB;

/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBDocenteRepository extends DBRepository
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
        $db = $this->getDb();

        $cond = $field . ' = ';

        $query = 'SELECT id_utente,	cod_doc, nome_doc FROM docente WHERE '
                . $cond . $db->quote($id);
        //		var_dump($query); die;
        $res = $db->query($query);
        if (DB::isError($res)) {
            $this
                    ->throwError('_ERROR_CRITICAL',
                            array('msg' => DB::errorMessage($res),
                                    'file' => __FILE__, 'line' => __LINE__));
        }

        $rows = $res->numRows();
        if ($rows == 0) {
            $ret = false;

            return $ret;
        }

        $row = $this->fetchRow($res);
        $docente = new Docente($row[0], $row[1], $row[2]);

        return $docente;
    }
    
    public function getInfo(Docente $docente)
    {
        $db = $this->getDb();
        
        $query = 'SELECT nome, cognome, prefissonome, sesso, email, descrizionestruttura FROM rub_docente WHERE cod_doc = '
        . $db->quote($docente->getCodDoc());
        $res = $db->query($query);
        if (DB::isError($res))
        	Error::throwError(_ERROR_CRITICAL,
        			array('msg' => DB::errorMessage($res), 'file' => __FILE__,
        					'line' => __LINE__));
        
        $rows = $res->numRows();
        if ($rows == 0)
        
        	return false;
        
        $row = $this->fetchRow($res);
        
        $rubrica = array_combine(
        		array('nome', 'cognome', 'prefissonome', 'sesso', 'email',
        				'descrizionestruttura'), $row);
        
        $res->free();
        
        return $rubrica;
    }
}
