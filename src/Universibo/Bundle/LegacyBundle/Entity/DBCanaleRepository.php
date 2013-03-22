<?php

namespace Universibo\Bundle\LegacyBundle\Entity;

use DateTime;
use Universibo\Bundle\MainBundle\Entity\Channel;
use Universibo\Bundle\MainBundle\Entity\ChannelRepository;
use Universibo\Bundle\MainBundle\Entity\ChannelServiceRepository;

/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBCanaleRepository
{
    private $channelRepository;
    private $serviceMap;

    public function __construct(ChannelRepository $channelRepository, ChannelServiceRepository $channelServiceRepository)
    {
        $this->channelRepository = $channelRepository;

        $this->serviceMap = [
            'getServizioFiles'         => $channelServiceRepository->findOneByName('files'),
            'getServizioForum'         => $channelServiceRepository->findOneByName('forum'),
            'getServizioLinks'         => $channelServiceRepository->findOneByName('links'),
            'getServizioNews'          => $channelServiceRepository->findOneByName('news'),
            'getServizioFilesStudenti' => $channelServiceRepository->findOneByName('student_files'),
        ];
    }

    public function getTipoCanaleFromId($id)
    {
        $channel = $this->channelRepository->find($id);

        return (int) $channel->getType();
    }

    public function updateUltimaModifica(Canale $canale)
    {
        $channel = $this->channelRepository->find($canale->getIdCanale());

        $updated = new DateTime();
        $updated->setTimestamp($canale->getUltimaModifica());
        $channel->setUpdatedAt($updated);

        $this->channelRepository->save($channel);
    }

    public function find($idCanale)
    {
        $result = $this->findManyById(array($idCanale));

        return is_array($result) && count($result) > 0 ? $result[0] : null;
    }

    public function findManyById(array $idCanale)
    {
        $found = array();

        if (count($idCanale) > 0) {
            foreach ($this->channelRepository->findManyById($idCanale) as $channel) {
                $found[] = $this->channelToCanale($channel);
            }
        }

        return $found;
    }

    /**
     * Finds channel ids given a type
     *
     * @param  integer   $type
     * @return integer[]
     */
    public function findManyByType($type)
    {
        $elenco_canali = array();

        foreach ($this->channelRepository->findByType($type) as $channel) {
            $elenco_canali[] = $channel->getId();
        }

        return $elenco_canali;
    }

    public function addVisite(Canale $canale, $increment = 1)
    {
        $channel = $this->channelRepository->find($canale->getIdCanale());
        $channel->setHits($channel->getHits() + $increment);

        $this->channelRepository->save($channel);

        return true;
    }

    public function insert(Canale $canale)
    {
        $channel = new Channel();

        $this->setChannelFields($canale, $channel);

        return true;
    }

    private function setChannelFields(Canale $canale, Channel $channel)
    {
        $services = $channel->getServices();
        foreach ($this->serviceMap as $methodName => $service) {
            if (call_user_func(array($canale, $methodName))) {
                $services->add($service);
            } else {
                $services->removeElement($service);
            }
        }

        if ($canale->getServizioForum()) {
            $channel->setForumId($canale->getForumForumId());
        }

        $channel->setType($canale->getTipoCanale());
        $channel->setName($canale->getNomeCanale());
        $channel->setHits($canale->getVisite());
        $updatedAt = new \DateTime();
        $updatedAt->setTimestamp($canale->getUltimaModifica());
        $channel->setLegacyGroups($canale->getPermessi());

        $this->channelRepository->save($channel);
    }

    public function update(Canale $canale)
    {
        $channel = $this->channelRepository->find($canale->getIdCanale());

        if ($channel !== null) {
            $this->setChannelFields($canale, $channel);

            return true;
        }

        return false;
    }

    public function idExists($id)
    {
        return $this->channelRepository->find($id) !== null;
    }

    private function channelToCanale(Channel $channel = null)
    {
        if (!$channel) {
            return null;
        }

        $timestamp = $channel->getUpdatedAt() ? $channel->getUpdatedAt()->getTimestamp() : null;

        return new Canale(
                $channel->getId(),
                $channel->getLegacyGroups(),
                $timestamp,
                $channel->getType(),
                '',
                $channel->getName(),
                $channel->getHits(),
                $this->hasService($channel, 'news'),
                $this->hasService($channel, 'files'),
                $this->hasService($channel, 'forum'),
                $channel->getForumId(),
                0,
                $this->hasService($channel, 'links'),
                $this->hasService($channel, 'student_files')
        );
    }

    private function hasService(Channel $channel, $serviceName)
    {
        foreach ($channel->getServices() as $service) {
            if ($service->getName() === $serviceName) {
                return true;
            }
        }

        return false;
    }
}
