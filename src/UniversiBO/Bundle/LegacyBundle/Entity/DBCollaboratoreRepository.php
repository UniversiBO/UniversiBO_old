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
class DBCollaboratoreRepository extends DBRepository
{
    public function find($id)
    {
        $db = $this->getDb();

        $query = 'SELECT id_utente,	intro, recapito, obiettivi, foto, ruolo FROM collaboratore WHERE id_utente = '
                . $db->quote($id);
        $res = $db->query($query);
        if (DB::isError($res))
            $this->throwError('_ERROR_CRITICAL',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

        $rows = $res->numRows();
        if ($rows == 0)

            return false;

        $row = $this->fetchRow($res);

        $collaboratore = new Collaboratore($row[0], $row[1], $row[2], $row[3],
                $row[4], $row[5]);

        $userRepo = new DBUserRepository($db);

        if (($user = $userRepo->find($collaboratore->getIdUser())) instanceof User) {
            $collaboratore->setUser($user);
        }


        return $collaboratore;
    }

    public function findAll($shownOnly = false)
    {
        $db = $this->getDb();

        $userRepo = new DBUserRepository($db);

        $query = 'SELECT id_utente,	intro, recapito, obiettivi, foto, ruolo FROM collaboratore';

        if ($shownOnly) {
            $query .= ' WHERE show = ' . $db->quote('Y');
        }

        $res = $db->query($query);
        if (DB::isError($res))
            $this->throwError('_ERROR_CRITICAL',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));

        $rows = $res->numRows();

        $collaboratori = array();

        while ($row = $this->fetchRow($res)) {
            $collaboratori[] = $collab = new Collaboratore($row[0], $row[1],
                    $row[2], $row[3], $row[4], $row[5]);
            $collab->setUser($userRepo->find($collab->getIdUser()));
        }


        return $collaboratori;
    }

    public function insert(Collaboratore $collaboratore)
    {
        ignore_user_abort(1);
        $db->autoCommit(false);
        $return = true;

        //TODO fare inserimento solo se non già presente
        $query = 'INSERT INTO collaboratore (id_utente, intro, recapito, obiettivi, foto, ruolo) VALUES '
                . '( ' . $db->quote($collaboratore->getIdUtente()) . ' , '
                . $db->quote($collaboratore->getIntro()) . ' , '
                . $db->quote($collaboratore->getRecapito()) . ' , '
                . $db->quote($collaboratore->getObiettivi()) . ' , '
                . $db->quote($collaboratore->getFotoFilename()) . ' , '
                . $db->quote($collaboratore->getRuolo()) . ' )';

        $res = $db->query($query);
        //var_dump($query);
        if (DB::isError($res)) {
            $db->rollback();
            $this
                    ->throwError('_ERROR_CRITICAL',
                            array('msg' => DB::errorMessage($res),
                                    'file' => __FILE__, 'line' => __LINE__));
        }

        $db->commit();
        $db->autoCommit(true);
        ignore_user_abort(0);
    }
}
