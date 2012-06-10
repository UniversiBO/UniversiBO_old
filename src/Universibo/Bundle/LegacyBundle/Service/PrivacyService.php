<?php
namespace Universibo\Bundle\LegacyBundle\Service;

/**
 * Encapsulates privacy management
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
use Universibo\Bundle\LegacyBundle\Entity\DBInformativaRepository;
use Universibo\Bundle\LegacyBundle\Entity\User;
use Universibo\Bundle\LegacyBundle\Entity\DoctrineRepository;

use Doctrine\DBAL\Connection;

class PrivacyService extends DoctrineRepository
{
    const CLASSNAME = 'Universibo\\Bundle\\LegacyBundle\\Command\\InteractiveCommand\\InformativaPrivacyInteractiveCommand';

    /**
     * @var DBInformativaRepository
     */
    private $informativaRepository;

    /**
     * @param Connection              $connection
     * @param DBInformativaRepository $informativaRepository
     */
    public function __construct(Connection $connection, DBInformativaRepository $informativaRepository)
    {
        parent::__construct($connection);

        $this->informativaRepository = $informativaRepository;
    }

    public function hasAcceptedPrivacy(User $user)
    {
        $builder = $this->getConnection()->createQueryBuilder();

        $stmt = $builder
            ->select('MAX(sl.data_ultima_interazione) AS latest')
            ->from('step_log', 'sl')
            ->where('sl.id_utente = ?')
            ->andWhere('sl.nome_classe = ?')
            ->andWhere('sl.esito_positivo = ?')
            ->setParameters(array($user->getIdUser(), self::CLASSNAME, 'S'))
            ->execute();

        $row = $stmt->fetch();

        if ($row === false) {
            return false;
        }

        $current = $this->informativaRepository->findByTime(time());

        return $row[0] >= $current->getDataPubblicazione();
    }

    public function markAccepted(User $user)
    {
        $conn = $this->getConnection();
        $conn->insert('step_log', array(
                'id_utente' => $user->getIdUser(),
                'data_ultima_interazione' => time(),
                'nome_classe' => self::CLASSNAME,
                'esito_positivo' => 'S'
        ));
    }
}
