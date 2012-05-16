<?php
namespace UniversiBO\Bundle\LegacyBundle\Entity;

use \DB;
use \Error;
use UniversiBO\Bundle\LegacyBundle\Framework\FrontController;

/**
 * Collaboratore class, modella le informazioni relative ai collaboratori
 *
 * @package universibo
 * @version 2.0.0
 * @author Ilias Bartolini <brain79@virgilio.it>
 * @license GPL, <{@link http://www.opensource.org/licenses/gpl-license.php}>
 * @copyright CopyLeft UniversiBO 2001-2004
 */

class Collaboratore extends User {
    /**
     * @var DBCdlRepository
     */
    private static $repository;

    /**
     * @access private
     */
    var $id_utente;

    /**
     * @access private
     */
    var $intro;

    /**
     * @access private
     */
    var $ruolo;

    /**
     * @access private
     */
    var $recapito;

    /**
     * @access private
     */
    var $obiettivi;

    /**
     * @access private
     */
    var $foto;

    /**
     * @access private
     */
    var $userCache = NULL;

    /**
     * @final
     * @access private
     */
    var $fotoDefault = 'no_foto.png';


    public function __construct($id_utente, $intro, $recapito, $obiettivi, $foto, $ruolo )
    {
        $this->id_utente	= $id_utente;
        $this->intro		= $intro;
        $this->ruolo		= $ruolo;
        $this->recapito 	= $recapito;
        $this->foto			= $foto;
        $this->obiettivi	= $obiettivi;
    }

    function getIdUtente()
    {
        return $this->id_utente;
    }

    function setIdUtente($id_utente)
    {
        $this->id_utente = $id_utente;
    }

    function getIntro()
    {
        return $this->intro;
    }

    function setIntro($intro)
    {
        $this->intro = $intro;
    }

    function getRuolo()
    {
        return $this->ruolo;
    }

    function setRuolo($ruolo)
    {
        $this->ruolo = $ruolo;
    }

    function getRecapito()
    {
        return $this->recapito;
    }

    function setRecapito($recapito)
    {
        $this->recapito = $recapito;
    }

    function getObiettivi()
    {
        return $this->obiettivi;
    }

    function setObiettivi($obiettivi)
    {
        $this->obiettivi = $obiettivi;
    }

    function getFotoFilename()
    {
        return ($this->foto != NULL) ? $this->getIdUtente().'_'.$this->foto : $this->fotoDefault;
    }

    function setFotoFilename($foto)
    {
        $this->foto = $foto;
    }


    /**
     * Ritorna Preleva tutti i collaboratori dal database
     *
     * @todo non si capisce una mazza
     * @param int $id_utente numero identificativo utente
     * @return array Collaboratori
     */
    function getUser()
    {
        if ($this->userCache == NULL)
        {
            $this->userCache = User::selectUser($this->getIdUtente());
        }

        return $this->userCache;
    }


    /**
     * Ritorna un collaboratori dato l'id_utente del database
     *
     * @deprecated
     * @param int $id_utente numero identificativo utente
     * @return array Collaboratori
     */
    public static function selectCollaboratore($id)
    {
        return self::getRepository()->find($id);
    }

    /**
     * Preleva tutti i collaboratori dal database
     *
     * @deprecated
     * @param int $id_utente numero identificativo utente
     * @return array Collaboratori
     */
    public static function selectCollaboratoriAll()
    {
        return self::getRepository()->findAll();
    }

    /**
     * Inserisce il profilo di un nuovo collaboratore sul DB
     *
     * @deprecated
     * @return	 boolean true se avvenua con successo, altrimenti Error object
     */
    public static function insertCollaboratoreItem()
    {

        return self::getRepository()->insert($this);
    }

    /**
     * @return DBCollaboratoreRepository
     */
    private static function getRepository()
    {
        if(is_null(self::$repository))
        {
            self::$repository = new DBCollaboratoreRepository(FrontController::getDbConnection('main'));
        }

        return self::$repository;
    }
}
