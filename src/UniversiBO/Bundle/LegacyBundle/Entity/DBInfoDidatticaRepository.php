<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity;
use \DB;

/**
 * @todo Informativa Entity
 */
class DBInfoDidatticaRepository extends DBRepository
{
    public function find($id)
    {
        $db = $this->getDb();

        $query = 'SELECT id_canale, programma, programma_link, testi_consigliati, testi_consigliati_link,
        modalita, modalita_link, obiettivi_esame, obiettivi_esame_link, appelli, appelli_link,
        homepage_alternativa_link,orario_ics_link
        FROM info_didattica WHERE
        id_canale = ' . $db->quote($id);

        $res = $db->query($query);
        //var_dump($query);
        //var_dump($res);

        if (DB::isError($res)) {
            $db->rollback();
            $this
                    ->throwError('_ERROR_CRITICAL',
                            array('msg' => DB::errorMessage($res),
                                    'file' => __FILE__, 'line' => __LINE__));
        }

        if ($row = $this->fetchRow($res)) {
            return new InfoDidattica($row[0], $row[1], $row[2], $row[3],
                    $row[4], $row[5], $row[6], $row[7], $row[8], $row[9],
                    $row[10], $row[11], $row[12]);
        }

        return false;
    }

    public function delete(InfoDidattica $infoDidattica)
    {
        $db = $this->getDb();

        $query = 'DELETE FORM info_didattica  WHERE id_canale = '
        . $db->quote($infoDidattica->getIdCanale());

        $res = $db->query($query);
        //var_dump($query);
        if (DB::isError($res)) {
            $db->rollback();
            $this->throwError('_ERROR_CRITICAL',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
        }


        return true;
    }

    public function insert(InfoDidattica $infoDidattica)
    {
        $db = $this->getDb();

        $query = 'INSERT INTO info_didattica (id_canale, programma, programma_link, testi_consigliati,
        testi_consigliati_link, modalita, modalita_link, obiettivi_esame,
        obiettivi_esame_link, appelli, appelli_link, homepage_alternativa_link, orario_ics_link) VALUES '
        . '( ' . $db->quote($infoDidattica->getIdCanale()) . ' , '
        . $db->quote($infoDidattica->getProgramma()) . ' , '
        . $db->quote($infoDidattica->getProgrammaLink()) . ' , '
        . $db->quote($infoDidattica->getTestiConsigliati()) . ' , '
        . $db->quote($infoDidattica->getTestiConsigliatiLink()) . ' , '
        . $db->quote($infoDidattica->getModalita()) . ' , '
        . $db->quote($infoDidattica->getModalitaLink()) . ' , '
        . $db->quote($infoDidattica->getObiettiviEsame()) . ' , '
        . $db->quote($infoDidattica->getObiettiviEsameLink()) . ' , '
        . $db->quote($infoDidattica->getAppelli()) . ' , '
        . $db->quote($infoDidattica->getAppelliLink()) . ' , '
        . $db->quote($infoDidattica->getHomepageAlternativaLink()) . ' , '
        . $db->quote($infoDidattica->getOrarioIcsLink()) . ' )';

        $res = $db->query($query);
        //var_dump($query);
        if (DB::isError($res)) {
            $db->rollback();
            $this->throwError('_ERROR_CRITICAL',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
        }


        return true;
    }

    public function update(InfoDidattica $infoDidattica)
    {
        $db = $this->getDb();


        $query = 'UPDATE info_didattica SET ' . ' programma = '
        . $db->quote($infoDidattica->getProgramma()) . ', programma_link = '
        . $db->quote($infoDidattica->getProgrammaLink())
        . ', testi_consigliati = '
        . $db->quote($infoDidattica->getTestiConsigliati())
        . ', testi_consigliati_link = '
        . $db->quote($infoDidattica->getTestiConsigliatiLink())
        . ', modalita = ' . $db->quote($infoDidattica->getModalita())
        . ', modalita_link = ' . $db->quote($infoDidattica->getModalitaLink())
        . ', obiettivi_esame = '
        . $db->quote($infoDidattica->getObiettiviEsame())
        . ', obiettivi_esame_link = '
        . $db->quote($infoDidattica->getObiettiviEsameLink()) . ', appelli = '
        . $db->quote($infoDidattica->getAppelli()) . ', appelli_link = '
        . $db->quote($infoDidattica->getAppelliLink())
        . ', homepage_alternativa_link= '
        . $db->quote($infoDidattica->getHomepageAlternativaLink())
        . ', orario_ics_link= ' . $db->quote($infoDidattica->getOrarioIcsLink())
        . ' WHERE id_canale = ' . $db->quote($infoDidattica->getIdCanale());

        $res = $db->query($query);
        //var_dump($query);
        if (DB::isError($res)) {
            $db->rollback();
            $this->throwError('_ERROR_CRITICAL',
                    array('msg' => DB::errorMessage($res), 'file' => __FILE__,
                            'line' => __LINE__));
        }


        return true;
    }
}
