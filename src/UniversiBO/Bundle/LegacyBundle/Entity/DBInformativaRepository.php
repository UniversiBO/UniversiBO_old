<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity;

use \DB;
use \Error;

/**
 * @todo Informativa Entity
 */
class DBInformativaRepository extends DBRepository
{
    /**
     * @param int $time
     * @return array
     */
    public function findByTime($time)
    {
        $db = $this->getDb();
        
        $query = 'SELECT id_informativa, testo FROM  informativa
        WHERE data_pubblicazione <= '.$db->quote( $time ).
        ' AND  (data_fine IS NULL OR data_fine > '.$db->quote( $time ).')' .
        'ORDER BY id_informativa DESC';  // VERIFY così possiamo già pianificare quando una certa informativa scadrà
        
        $res = $db->query($query);
        if (DB::isError($res))
        	Error::throwError(_ERROR_DEFAULT,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        
        $rows = $res->numRows();
        
        if( $rows = 0) return array();
        
        $list = array();
        $res->fetchInto($row);
        $list['id_info'] = $row[0];
        $list['testo']= $row[1];
        $res->free();
        
        return $list;
    }
}