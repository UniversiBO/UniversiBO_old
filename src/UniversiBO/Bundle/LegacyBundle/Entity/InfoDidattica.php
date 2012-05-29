<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity;
use \DB;
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

    /**
     * @var DBInfoDidatticaRepository
     */
    private static $repository;

    public function __construct($id_canale, $programma, $programma_link,
            $testi_consigliati, $testi_consigliati_link, $modalita,
            $modalita_link, $obiettivi_esame, $obiettivi_esame_link, $appelli,
            $appelli_link, $homepage_alternativa_link, $orario_ics_link)
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
        $this->id_canale = $id_canale;
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
        $this->programma = $programma;
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
        $this->testi_consigliati_link = $testi_consigliati_link;
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
        $this->modalita = $modalita;
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
        $this->modalita_link = $modalita_link;
    }

    /**
     * Restituisce
     *
     * @return string
     */
    function getModalitaLink()
    {
        return $this->modalita_link;
    }

    /**
     * Imposta
     *
     * @param string
     */
    function setObiettiviEsame($obiettivi_esame)
    {
        $this->obiettivi_esame = $obiettivi_esame;
    }

    /**
     * Restituisce
     *
     * @return int
     */
    function getObiettiviEsame()
    {
        return $this->obiettivi_esame;
    }

    /**
     * Imposta
     *
     * @param string
     */
    function setObiettiviEsameLink($obiettivi_esame_link)
    {
        $this->obiettivi_esame_link = $obiettivi_esame_link;
    }

    /**
     * Restituisce
     *
     * @return string
     */
    function getObiettiviEsameLink()
    {
        return $this->obiettivi_esame_link;
    }

    /**
     * Imposta
     *
     * @param string
     */
    function setAppelli($appelli)
    {
        $this->appelli = $appelli;
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
        $this->appelli_link = $appelli_link;
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
        $this->homepage_alternativa_link = $homepage_alternativa_link;
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
        $this->orario_ics_link = $orario_ics_link;
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
    public function insertInfoDidattica()
    {
        return self::getRepository()->insert($this);
    }

    /**
     * Modifica una InfoDidattica sul DB
     *
     * @return	 boolean true se avvenua con successo
     */
    public function updateInfoDidattica()
    {
        return self::getRepository()->update($this);
    }

    /**
     * Elimina una InfoDidattica sul DB
     *
     * @return	 boolean true se avvenua con successo
     */
    public function deleteInfoDidattica()
    {
        return self::getRepository()->delete($this);
    }

    /**
     * Seleziona una InfoDidattica da DB dato il suo numero identificativo
     *
     * @deprecated
     * @return InfoDidattica
     */
    public static function retrieveInfoDidattica($id_canale)
    {
        return self::factoryInfoDidattica($id_canale);
    }

    /**
     * Seleziona una InfoDidattica da DB dato il suo numero identificativo
     *
     * @deprecated
     * @return InfoDidattica
     */
    public static  function factoryInfoDidattica($id_canale)
    {
        return self::selectInfoDidattica($id_canale);
    }

    /**
     * Seleziona una InfoDidattica da DB dato il suo numero identificativo
     *
     * @deprecated
     * @return InfoDidattica
     */
    public static function selectInfoDidattica($id_canale)
    {
        return self::getRepository()->find($id_canale);
    }

    /**
     * @return DBInfoDidatticaRepository
     */
    private static function getRepository()
    {
        if (is_null(self::$repository)) {
            self::$repository = new DBInfoDidatticaRepository(
                    FrontController::getDbConnection('main'));
        }

        return self::$repository;
    }
}
