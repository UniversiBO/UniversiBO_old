<?php

namespace UniversiBO\Bundle\LegacyBundle\Entity;

/**
 * Canale repository
 *
 * @author Davide Bellettini <davide.bellettini@gmail.com>
 * @license GPL v2 or later
 */
class DBCanale2Repository extends DBRepository
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
     * Class constructor
     *
     * @param \DB_common          $db
     * @param DBCanaleRepository  $channelRepository
     * @param DBCdlRepository     $cdlRepository
     * @param DBFacoltaRepository $facultyRepository
     * @param boolean             $convert
     */
    public function __construct(\DB_common $db,
            DBCanaleRepository $channelRepository,
            DBCdlRepository $cdlRepository,
            DBFacoltaRepository $facultyRepository, $convert = False)
    {
        parent::__construct($db, $convert);

        $this->channelRepository = $channelRepository;
        $this->cdlRepository = $cdlRepository;
        $this->facultyRepository = $facultyRepository;
    }

    public function findManyByType($type)
    {
        switch ($type) {
            case Canale::FACOLTA:
                return $this->facultyRepository->findAll();
            case Canale::CDL:
                return $this->cdlRepository->findAll();
            case Canale::INSEGNAMENTO:
            default:
                $ids = $this->channelRepository->findManyByType($type);

                return $this->channelRepository->findManyById($ids);
        }
    }

    public function find($id)
    {
        $db = $this->getDb();

        $sql = 'SELECT tipo_canale FROM canale WHERE id_canale = ' . $db->quote($id);

        $res = $db->query($sql);
        if(\DB::isError($res)) {
            throw new \Exception($res->getMessage());
        }

        $row = $this->fetchRow($res);
        switch ($row[0]) {
            case Canale::FACOLTA:
                return $this->facultyRepository->find($id);
            case Canale::CDL:
                return $this->cdlRepository->find($id);
            default:
                return $this->channelRepository->find($id);
        }
    }
}
