<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity;

use \DB;
use \Error;
use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;

/**
 * InfoDidattica class.
 *
 * Modella le informazioni per la didattica.
 * Separata da Insegnamento per non appesantirlo
 *
 * @package universibo
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, @link http://www.opensource.org/licenses/gpl-license.php
 * @copyright CopyLeft UniversiBO 2001-2004
 */

class InfoDidattica
{


    /**
     * @private
     */
    var $id_canale = 0;
    /**
     * @private
     */
    var $programma = '';
    /**
     * @private
     */
    var $programma_link = '';
    /**
     * @private
     */
    var $testi_consigliati = '';
    /**
     * @private
     */
    var $testi_consigliati_link = '';
    /**
     * @private
     */
    var $modalita = '';
    /**
     * @private
     */
    var $modalita_link = '';
    /**
     * @private
     */
    var $obiettivi_esame = '';
    /**
     * @private
     */
    var $obiettivi_esame_link = '';
    /**
     * @private
     */
    var $appelli = '';
    /**
     * @private
     */
    var $appelli_link = '';
    /**
     * @private
     */
    var $homepage_alternativa_link = '';

    /**
     * @private
     */
    var $orario_ics_link = '';

    public function __construct($id_canale, $programma, $programma_link, $testi_consigliati,
                        $testi_consigliati_link, $modalita, $modalita_link, $obiettivi_esame,
                        $obiettivi_esame_link, $appelli, $appelli_link, $homepage_alternativa_link, $orario_ics_link)
    {
        $this->id_canale = $id_canale;
        $this->programma = $programma;
        $this->programma_link = $programma_link;
        $this->testi_consigliati = $testi_consigliati;
        $this->testi_consigliati_link = $testi_consigliati_link;
        $this->modalita = $modalita;
        $this->modalita_link = $modalita_link;
        $this->obiettivi_esame = $obiettivi_esame;
        $this->obiettivi_esame_link = $obiettivi_esame_link;
        $this->appelli = $appelli;
        $this->appelli_link = $appelli_link;
        $this->homepage_alternativa_link = $homepage_alternativa_link;
        $this->orario_ics_link = $orario_ics_link;
    }

    /**
     * Imposta
     *
     * @param int
     */
    function setIdCanale($id_canale)
    {
        $this->id_canale  = $id_canale;
    }

    /**
     * Restituisce
     *
     * @return int
     */
    function getIdCanale()
    {
        return $this->id_canale;
    }


    /**
     * Imposta
     *
     * @param string
     */
    function setProgramma($programma)
    {
        $this->programma  = $programma ;
    }

    /**
     * Restituisce
     *
     * @return string
     */
    function getProgramma()
    {
        return $this->programma;
    }


    /**
     * Imposta
     *
     * @param string
     */
    function setProgrammaLink($programma_link)
    {
        $this->programma_link = $programma_link;
    }

    /**
     * Restituisce
     *
     * @return string
     */
    function getProgrammaLink()
    {
        return $this->programma_link;
    }


    /**
     * Imposta
     *
     * @param string
     */
    function setTestiConsigliati($testi_consigliati)
    {
        $this->testi_consigliati = $testi_consigliati;
    }

    /**
     * Restituisce
     *
     * @return string
     */
    function getTestiConsigliati()
    {
        return $this->testi_consigliati;
    }


    /**
     * Imposta
     *
     * @param string
     */
    function setTestiConsigliatiLink($testi_consigliati_link)
    {
        $this->testi_consigliati_link = $testi_consigliati_link ;
    }

    /**
     * Restituisce
     *
     * @return string
     */
    function getTestiConsigliatiLink()
    {
        return $this->testi_consigliati_link;
    }

    /**
     * Imposta
     *
     * @param string
     */
    function setModalita($modalita)
    {
        $this->modalita  =  $modalita;
    }

    /**
     * Restituisce
     *
     * @return string
     */
    function getModalita()
    {
        return $this->modalita;
    }

    /**
     * Imposta
     *
     * @param string
     */
    function setModalitaLink($modalita_link)
    {
        $this->modalita_link  = $modalita_link ;
    }

    /**
     * Restituisce
     *
     * @return string
     */
    function getModalitaLink()
    {
        return $this->modalita_link  ;
    }

    /**
     * Imposta
     *
     * @param string
     */
    function setObiettiviEsame($obiettivi_esame)
    {
        $this->obiettivi_esame = $obiettivi_esame ;
    }

    /**
     * Restituisce
     *
     * @return int
     */
    function getObiettiviEsame()
    {
        return $this->obiettivi_esame  ;
    }

    /**
     * Imposta
     *
     * @param string
     */
    function setObiettiviEsameLink($obiettivi_esame_link)
    {
        $this->obiettivi_esame_link = $obiettivi_esame_link ;
    }

    /**
     * Restituisce
     *
     * @return string
     */
    function getObiettiviEsameLink()
    {
        return $this->obiettivi_esame_link  ;
    }


    /**
     * Imposta
     *
     * @param string
     */
    function setAppelli($appelli)
    {
        $this->appelli  = $appelli ;
    }

    /**
     * Restituisce
     *
     * @return string
     */
    function getAppelli()
    {
        return $this->appelli;
    }


    /**
     * Imposta
     *
     * @param string
     */
    function setAppelliLink($appelli_link)
    {
        $this->appelli_link  = $appelli_link ;
    }

    /**
     * Restituisce
     *
     * @return string
     */
    function getAppelliLink()
    {
        return $this->appelli_link;
    }


    /**
     * Imposta
     *
     * @param string
     */
    function setHomepageAlternativaLink($homepage_alternativa_link)
    {
        $this->homepage_alternativa_link  = $homepage_alternativa_link ;
    }


    /**
     * Restituisce
     *
     * @return string
     */
    function getHomepageAlternativaLink()
    {
        return $this->homepage_alternativa_link;
    }

    /**
     * Imposta
     *
     * @param string
     */
    function setOrarioIcsLink($orario_ics_link)
    {
        $this->orario_ics_link  = $orario_ics_link ;
    }


    /**
     * Restituisce
     *
     * @return string
     */
    function getOrarioIcsLink()
    {
        return $this->orario_ics_link;
    }


    /**
     * Inserisce una nuovo InfoDidattica sul DB
     *
     * @return	 boolean true se avvenua con successo
     */
    function insertInfoDidattica()
    {
        $db = FrontController::getDbConnection('main');

        $query = 'INSERT INTO info_didattica (id_canale, programma, programma_link, testi_consigliati,
                        testi_consigliati_link, modalita, modalita_link, obiettivi_esame,
                        obiettivi_esame_link, appelli, appelli_link, homepage_alternativa_link, orario_ics_link) VALUES '.
                    '( '.$db->quote($this->getIdCanale()).' , '.
                    $db->quote($this->getProgramma()).' , '.
                    $db->quote($this->getProgrammaLink()).' , '.
                    $db->quote($this->getTestiConsigliati()).' , '.
                    $db->quote($this->getTestiConsigliatiLink()).' , '.
                    $db->quote($this->getModalita()).' , '.
                    $db->quote($this->getModalitaLink()).' , '.
                    $db->quote($this->getObiettiviEsame()).' , '.
                    $db->quote($this->getObiettiviEsameLink()).' , '.
                    $db->quote($this->getAppelli()).' , '.
                    $db->quote($this->getAppelliLink()).' , '.
                    $db->quote($this->getHomepageAlternativaLink()).' , '.
                    $db->quote($this->getOrarioIcsLink()).' )';

        $res = $db->query($query);
        //var_dump($query);
        if (DB::isError($res))
        {
            $db->rollback();
            Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }


        return true;
    }


    /**
     * Modifica una InfoDidattica sul DB
     *
     * @return	 boolean true se avvenua con successo
     */
    function updateInfoDidattica()
    {
        $db = FrontController::getDbConnection('main');

        $query = 'UPDATE info_didattica SET '
                    .' programma = '.$db->quote($this->getProgramma())
                    .', programma_link = '.$db->quote($this->getProgrammaLink())
                    .', testi_consigliati = '.$db->quote($this->getTestiConsigliati())
                    .', testi_consigliati_link = '.$db->quote($this->getTestiConsigliatiLink())
                    .', modalita = '.$db->quote($this->getModalita())
                    .', modalita_link = '.$db->quote($this->getModalitaLink())
                    .', obiettivi_esame = '.$db->quote($this->getObiettiviEsame())
                    .', obiettivi_esame_link = '.$db->quote($this->getObiettiviEsameLink())
                    .', appelli = '.$db->quote($this->getAppelli())
                    .', appelli_link = '.$db->quote($this->getAppelliLink())
                    .', homepage_alternativa_link= '.$db->quote($this->getHomepageAlternativaLink())
                    .', orario_ics_link= '.$db->quote($this->getOrarioIcsLink()).
                    ' WHERE id_canale = '.$db->quote($this->getIdCanale());

        $res = $db->query($query);
        //var_dump($query);
        if (DB::isError($res))
        {
            $db->rollback();
            Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }


        return true;
    }


    /**
     * Elimina una InfoDidattica sul DB
     *
     * @return	 boolean true se avvenua con successo
     */
    function deleteInfoDidattica()
    {
        $db = FrontController::getDbConnection('main');

        $query = 'DELETE FORM info_didattica  WHERE id_canale = '.$db->quote($this->getIdCanale());

        $res = $db->query($query);
        //var_dump($query);
        if (DB::isError($res))
        {
            $db->rollback();
            Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }


        return true;
    }



    /**
     * Seleziona una InfoDidattica da DB dato il suo numero identificativo
     *
     * @return InfoDidattica
     */
    function retrieveInfoDidattica($id_canale)
    {
        $return = InfoDidattica::factoryInfoDidattica($id_canale);

        return $return;
    }


    /**
     * Seleziona una InfoDidattica da DB dato il suo numero identificativo
     *
     * @return InfoDidattica
     */
    function factoryInfoDidattica($id_canale)
    {
        $return = InfoDidattica::selectInfoDidattica($id_canale);

        return $return;
    }



    /**
     * Seleziona una InfoDidattica da DB dato il suo numero identificativo
     *
     * @return InfoDidattica
     */
    function selectInfoDidattica($id_canale)
    {
        $db = FrontController::getDbConnection('main');

        $query = 'SELECT id_canale, programma, programma_link, testi_consigliati, testi_consigliati_link,
                        modalita, modalita_link, obiettivi_esame, obiettivi_esame_link, appelli, appelli_link,
                        homepage_alternativa_link,orario_ics_link
                        FROM info_didattica WHERE
                        id_canale = '.$db->quote($id_canale);

        $res = $db->query($query);
        //var_dump($query);
        //var_dump($res);

        if (DB::isError($res))
        {
            $db->rollback();
            Error::throwError(_ERROR_CRITICAL,array('msg'=>DB::errorMessage($res),'file'=>__FILE__,'line'=>__LINE__));
        }

        if ($res->fetchInto($row))
        {
            $info_didattica = new InfoDidattica($row[0], $row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8], $row[9], $row[10], $row[11], $row[12] );

            return $info_didattica;
        }
        else

            return false;

    }

}
