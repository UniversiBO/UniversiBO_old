<?php

namespace Universibo\Bundle\LegacyBundle\Entity;
/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */

class Canale2Repository
{

    /**
     * @var CanaleRepository
     */
    private $channelRepository;

    /**
     * @var CdlRepository
     */
    private $cdlRepository;

    /**
     * @var FacoltaRepository
     */
    private $facultyRepository;

    /**
     * @var InsegnamentoRepository
     */
    private $subjectRepository;

    /**
     * Class constructor
     *
     * @param CanaleRepository         $channelRepository
     * @param CdlRepository            $cdlRepository
     * @param FacoltaRepository        $facultyRepository
     * @param DBInsegnamentoRepository $subjectRepository
     * @param boolean                  $convert
     */
    public function __construct(CanaleRepository $channelRepository,
            CdlRepository $cdlRepository,
            FacoltaRepository $facultyRepository,
            InsegnamentoRepository $subjectRepository)
    {
        $this->channelRepository = $channelRepository;
        $this->cdlRepository = $cdlRepository;
        $this->facultyRepository = $facultyRepository;
        $this->subjectRepository = $subjectRepository;
    }

    public function findManyByType($type)
    {
        switch ($type) {
        case Canale::FACOLTA:
            return $this->facultyRepository->findAll();
        case Canale::CDL:
            return $this->cdlRepository->findAll();
        default:
            $ids = $this->channelRepository->findManyByType($type);

            return $this->channelRepository->findManyById($ids);
        }
    }

    /**
     * @param  int        $id
     * @throws \Exception
     * @return Canale
     */
    public function find($id)
    {
        switch ($this->channelRepository->getTipoCanaleFromId($id)) {
        case Canale::FACOLTA:
            return $this->facultyRepository->find($id);
        case Canale::CDL:
            return $this->cdlRepository->findByIdCanale($id);
        case Canale::INSEGNAMENTO:
            return $this->subjectRepository->findByChannelId($id);
        default:
            return $this->channelRepository->find($id);
        }
    }
}
