<?php
namespace Universibo\Bundle\LegacyBundle\Service;

/**
 * Encapsulates privacy management
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 */
use Universibo\Bundle\LegacyBundle\Entity\InteractiveCommand\StepLog;

use Universibo\Bundle\LegacyBundle\Entity\InteractiveCommand\StepLogRepository;

use Universibo\Bundle\LegacyBundle\Entity\DBInformativaRepository;
use Universibo\Bundle\MainBundle\Entity\User;

use Doctrine\DBAL\Connection;

class PrivacyService
{
    const CLASSNAME = 'Universibo\\Bundle\\LegacyBundle\\Command\\InteractiveCommand\\InformativaPrivacyInteractiveCommand';

    /**
     * @var StepLogRepository
     */
    private $logRepository;

    /**
     * @var DBInformativaRepository
     */
    private $informativaRepository;

    /**
     * @param Connection              $connection
     * @param DBInformativaRepository $informativaRepository
     */
    public function __construct(StepLogRepository $logRepository, DBInformativaRepository $informativaRepository)
    {
        $this->logRepository = $logRepository;
        $this->informativaRepository = $informativaRepository;
    }

    public function hasAcceptedPrivacy(User $user)
    {
        $log = $this->logRepository->findLatestPositive($user->getId(), self::CLASSNAME);

        if ($log === null) {
            return false;
        }

        return $log->getDataUltimaInterazione() > $this->informativaRepository->findByTime(time())->getDataPubblicazione();
    }

    public function markAccepted(User $user)
    {
        $stepLog = new StepLog();
        $stepLog->setIdUtente($user->getId());
        $stepLog->setDataUltimaInterazione(time());
        $stepLog->setNomeClasse(self::CLASSNAME);
        $stepLog->setEsitoPositivo('S');
        $this->logRepository->insert($stepLog);
    }
}
