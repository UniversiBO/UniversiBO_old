<?php
namespace Universibo\Bundle\LegacyBundle\Entity;

use Universibo\Bundle\LegacyBundle\PearDB\DB;
use Doctrine\DBAL\DBALException;
use Universibo\Bundle\LegacyBundle\Framework\Error;
use Universibo\Bundle\CoreBundle\Entity\MergeableRepositoryInterface;
use Universibo\Bundle\CoreBundle\Entity\User;

/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBDocenteRepository extends DBRepository implements MergeableRepositoryInterface
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

    public function insert(Docente $docente)
    {
        $db = $this->getDb();

        $query = <<<EOT

INSERT INTO docente
    (id_utente, cod_doc, nome_doc)
VALUES
    (
        {$db->quote($docente->getIdUtente())},
        {$db->quote($docente->getCodDoc())},
        {$db->quote($docente->getNomeDoc())}
    )
EOT;
        $db->execute($query);
        if (DB::isError($res)) {
            throw new DBALException(DB::errorMessage($res));
        }
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

    /**
     * Counts how many professors are connected with this user
     * should be 0 or 1, but more if db has errors
     *
     * @param  User          $user
     * @return integer
     * @throws DBALException
     */
    public function countByUser(User $user)
    {
        $db = $this->getDb();

        $query = <<<EOT
SELECT COUNT(*)
    FROM docente
    WHERE id_utente = {$user->getId()}
EOT;
        $res = $db->query($query);
        if (DB::isError($res)) {
            throw new DBALException(DB::errorMessage($res));
        }

        $row = $this->fetchRow($res);
        $res->free();

        return $row[0];
    }

    /**
     * Transfers the ownership
     *
     * @param  User          $source
     * @param  User          $target
     * @throws DBALException
     */
    public function transferOwnership(User $source, User $target)
    {
        $db = $this->getDb();

        $query = <<<EOT
UPDATE docente
    SET id_utente = {$target->getId()}
    WHERE id_utente = {$source->getId()}
EOT;
        $res = $db->query($query);

        if (DB::isError($res)) {
            throw new DBALException(DB::errorMessage($res));
        }
    }
}
