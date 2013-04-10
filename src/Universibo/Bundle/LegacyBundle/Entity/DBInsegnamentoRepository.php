<?php

namespace Universibo\Bundle\LegacyBundle\Entity;

use Universibo\Bundle\LegacyBundle\PearDB\ConnectionWrapper;
use Universibo\Bundle\MainBundle\Entity\ChannelRepository;

/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBInsegnamentoRepository extends DBRepository
{
    /**
     * @var DBPrgAttivitaDidatticaRepository
     */
    private $programmaRepository;

    /**
     * Channel repository
     *
     * @var ChannelRepository
     */
    private $channelRepository;

    /**
     * Constructor
     *
     * @param ConnectionWrapper                $db
     * @param DBPrgAttivitaDidatticaRepository $programmaRepository
     * @param ChannelRepository                $channelRepository
     */
    public function __construct(ConnectionWrapper $db, DBPrgAttivitaDidatticaRepository $programmaRepository,
            ChannelRepository $channelRepository)
    {
        parent::__construct($db);

        $this->programmaRepository = $programmaRepository;
        $this->channelRepository = $channelRepository;
    }

    /**
     *
     * @param  integer              $channelId
     * @return boolean|Insegnamento
     */
    public function findByChannelId($channelId)
    {
        $channel = $this->channelRepository->find($channelId);

        if (null === $channel) {
            return null;
        }

        $elenco_attivita = $this->programmaRepository->findByChannelId($channelId);
        $ultima_modifica = $channel->getUpdatedAt() ? $channel->getUpdatedAt()->getTimestamp() : 0;

        return new Insegnamento($channel->getId(), $channel->getLegacyGroups(),
                    $ultima_modifica, $channel->getType(), '', $channel->getName(),
                    $channel->getHits(), $channel->hasService('news'), $channel->hasService('files'),
                    $channel->hasService('forum'), $channel->getForumId(),
                    $channel->getForumGroupId(), $channel->hasService('links'),
                    $channel->hasService('student_files'), $elenco_attivita);
    }

    /**
     * Finds all channels of type "Insegnamento"
     *
     * @param  boolean        $full
     * @return Insegnamento[]
     */
    public function findAll($full = false)
    {
        $channels = $this->channelRepository->findByType(Canale::INSEGNAMENTO);

        foreach ($channels as $channel) {
            $channelId = $channel->getId();
            $attivita = $full ? $this->programmaRepository->findByChannelId($channelId) : array();

            $ultima_modifica = $channel->getUpdatedAt() ? $channel->getUpdatedAt()->getTimestamp() : 0;

            $insegnamenti[] = new Insegnamento($channelId,
                    $channel->getLegacyGroups(), $ultima_modifica,
                    Canale::INSEGNAMENTO, '', $channel->getName(),
                    $channel->getHits(), $channel->hasService('news'),
                    $channel->hasService('files'), $channel->hasService('forum'),
                    $channel->getForumId(), $channel->getForumGroupId(),
                    $channel->hasService('links'), $channel->hasService('student_files'),$attivita);
        }

        return $insegnamenti;
    }
}
