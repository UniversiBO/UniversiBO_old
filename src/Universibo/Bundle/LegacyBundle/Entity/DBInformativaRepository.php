<?php
namespace Universibo\Bundle\LegacyBundle\Entity;

use \DB;

/**
 * @todo Informativa Entity
 */
class DBInformativaRepository extends DBRepository
{
    /**
     * @param  int         $time
     * @return Informativa
     */
    public function findByTime($time)
    {
        $db = $this->getDb();

        $query = 'SELECT id_informativa, data_pubblicazione, data_fine, testo FROM  informativa
        WHERE data_pubblicazione <= '.$db->quote( $time ).
        ' AND  (data_fine IS NULL OR data_fine > '.$db->quote( $time ).')' .
        'ORDER BY id_informativa DESC';  // TODO così possiamo già pianificare quando una certa informativa scadrà

        $res = $db->query($query);
        if (DB::isError($res)) {
            $this->throwError('_ERROR_DEFAULT',array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }

        $rows = $res->numRows();

        if( $rows = 0) return array();

        $list = array();
        $row = $this->fetchRow($res);
        $res->free();

        return $this->rowToInformativa($row);
    }

    private function rowToInformativa($row)
    {
        $informativa = new Informativa();

        $informativa->setId($row[0]);
        $informativa->setDataPubblicazione($row[1]);
        $informativa->setDataFine($row[2]);
        $informativa->setTesto($row[3]);

        return $informativa;
    }
}
