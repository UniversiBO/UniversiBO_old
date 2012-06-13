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
     * @var DBCanaleRepository
     */
    private $channelRepository;

    /**
     * @var DBCdlRepository
     */
    private $cdlRepository;

    /**
     * @var DBFacoltaRepository
     */
    private $facultyRepository;

    /**
     * @var DBInsegnamentoRepository
     */
    private $subjectRepository;

    /**
     * Class constructor
     *
     * @param DBCanaleRepository       $channelRepository
     * @param DBCdlRepository          $cdlRepository
     * @param DBFacoltaRepository      $facultyRepository
     * @param DBInsegnamentoRepository $subjectRepository
     * @param boolean                  $convert
     */
    public function __construct(DBCanaleRepository $channelRepository,
            DBCdlRepository $cdlRepository,
            DBFacoltaRepository $facultyRepository,
            DBInsegnamentoRepository $subjectRepository)
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
