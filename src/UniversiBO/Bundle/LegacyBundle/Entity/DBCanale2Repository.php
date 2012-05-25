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
    
    private $channelRepository;
    
    private $cdlRepository;
    
    private $facultyRepository;
    
    /**
     * Class constructor
     * 
     * @param \DB_common $db
     * @param DBCanaleRepository $channelRepository
     * @param DBCdlRepository $cdlRepository
     * @param DBFacoltaRepository $facultyRepository
     * @param unknown_type $convert
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
        	default:
        		return $this->channelRepository->findManyByType($type);
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
            case CANALE_FACOLTA:
                return $this->facultyRepository->find($id);
            case CANALE_CDL:
                return $this->cdlRepository->find($id);
            default:
                return $this->channelRepository->find($id); 
        }
    }
}
