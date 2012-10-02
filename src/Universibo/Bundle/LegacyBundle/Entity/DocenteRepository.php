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

        $cond = $field . ' = ';

        $query = 'SELECT id_utente,	cod_doc, nome_doc FROM docente WHERE '
                . $cond . $db->quote($id);
        //		var_dump($query); die;
        $res = $db->executeQuery($query);

        $rows = $res->rowCount();
        if ($rows == 0) {
            $ret = false;

            return $ret;
        }

        false !== ($row = $res->fetch(\PDO::FETCH_NUM));
        $docente = new Docente($row[0], $row[1], $row[2]);

        return $docente;
    }

    public function getInfo(Docente $docente)
    {
        $db = $this->getConnection();

        $query = 'SELECT nome, cognome, prefissonome, sesso, email, descrizionestruttura FROM rub_docente WHERE cod_doc = '
        . $db->quote($docente->getCodDoc());
        $res = $db->executeQuery($query);

        $rows = $res->rowCount();
        if ($rows == 0)
            return false;

        false !== ($row = $res->fetch(\PDO::FETCH_NUM));

        $rubrica = array_combine(
                array('nome', 'cognome', 'prefissonome', 'sesso', 'email',
                        'descrizionestruttura'), $row);

        return $rubrica;
    }
}
