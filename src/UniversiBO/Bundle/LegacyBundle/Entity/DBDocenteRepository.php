<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity;
use \DB;
use \Error;

/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBDocenteRepository extends DBRepository
{
    /**
     * Class constructor
     *
     * @param \DB_common $db
     */
    public function __construct(\DB_common $db)
    {
        parent::__construct($db);
    }

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

        $row = $res->fetchRow();
        $docente = new Docente($row[0], $row[1], $row[2]);

        return $docente;
    }
}
