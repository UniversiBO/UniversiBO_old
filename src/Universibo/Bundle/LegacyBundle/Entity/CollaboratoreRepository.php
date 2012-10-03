<?php
namespace Universibo\Bundle\LegacyBundle\Entity;

use Doctrine\DBAL\Connection;

use Universibo\Bundle\CoreBundle\Entity\UserRepository;

/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class CollaboratoreRepository extends DoctrineRepository
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param Connection     $db
     * @param UserRepository $userRepository
     */
    public function __construct(Connection $db, UserRepository $userRepository)
    {
        parent::__construct($db);

        $this->userRepository = $userRepository;
    }

    public function find($id)
    {
        $db = $this->getConnection();

        $query = 'SELECT id_utente,	intro, recapito, obiettivi, foto, ruolo FROM collaboratore WHERE id_utente = '
                . $db->quote($id);
        $res = $db->executeQuery($query);

        $rows = $res->rowCount();
        if ($rows == 0)
            return false;

        false !== ($row = $res->fetch(\PDO::FETCH_NUM));

        $collaboratore = new Collaboratore($row[0], $row[1], $row[2], $row[3],
                $row[4], $row[5]);

        $userRepo = $this->userRepository;

        if (($user = $userRepo->find($collaboratore->getIdUtente())) instanceof User) {
            $collaboratore->setUser($user);
        }

        return $collaboratore;
    }

    public function findOneByIdUtente($id)
    {
        $db = $this->getConnection();

        $query = 'SELECT id_utente,	intro, recapito, obiettivi, foto, ruolo FROM collaboratore WHERE id_utente = '
        . $db->quote($id);
        $res = $db->executeQuery($query);

        $rows = $res->rowCount();
        if ($rows == 0)
            return false;

        false !== ($row = $res->fetch(\PDO::FETCH_NUM));

        $collaboratore = new Collaboratore($row[0], $row[1], $row[2], $row[3],
                $row[4], $row[5]);

        $userRepo = $this->userRepository;

        if (($user = $userRepo->find($collaboratore->getIdUtente())) instanceof User) {
            $collaboratore->setUser($user);
        }

        return $collaboratore;
    }

    public function findAll($shownOnly = false)
    {
        $db = $this->getConnection();

        $userRepo = $this->userRepository;

        $query = 'SELECT id_utente,	intro, recapito, obiettivi, foto, ruolo FROM collaboratore';

        if ($shownOnly) {
            $query .= ' WHERE show = ' . $db->quote('Y');
        }

        $res = $db->executeQuery($query);

        $rows = $res->rowCount();

        $collaboratori = array();

        while (false !== ($row = $res->fetch(\PDO::FETCH_NUM))) {
            $collaboratori[] = $collab = new Collaboratore($row[0], $row[1],
                    $row[2], $row[3], $row[4], $row[5]);
            $collab->setUser($userRepo->find($collab->getIdUtente()));
        }

        return $collaboratori;
    }

    public function insert(Collaboratore $collaboratore)
    {
        ignore_user_abort(1);
        $db->beginTransaction();
        $return = true;

        //TODO fare inserimento solo se non già presente
        $query = 'INSERT INTO collaboratore (id_utente, intro, recapito, obiettivi, foto, ruolo) VALUES '
                . '( ' . $db->quote($collaboratore->getIdUtente()) . ' , '
                . $db->quote($collaboratore->getIntro()) . ' , '
                . $db->quote($collaboratore->getRecapito()) . ' , '
                . $db->quote($collaboratore->getObiettivi()) . ' , '
                . $db->quote($collaboratore->getFotoFilename()) . ' , '
                . $db->quote($collaboratore->getRuolo()) . ' )';

        $res = $db->executeQuery($query);
        //var_dump($query);

        $db->commit();
        ignore_user_abort(0);
    }
}
